<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Gadget;
use App\Models\PlatformSetting;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerCartController extends Controller
{
    public function index(Request $request): View
    {
        $customer = $this->customer($request);
        $cartItems = $customer->cartItems()
            ->with('gadget.lender:id,name,store_name')
            ->latest()
            ->get();

        return view('Customer_Page.cart', [
            'customer' => $customer,
            'cartItems' => $cartItems,
        ]);
    }

    public function requestRentals(Request $request): RedirectResponse
    {
        $customer = $this->customer($request);
        $validated = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $rentalDays = (int) $startDate->diffInDays($endDate);
        $createdCount = DB::transaction(function () use ($customer, $startDate, $endDate, $rentalDays): int {
            $cartItems = $customer->cartItems()
                ->orderBy('gadget_id')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Add at least one gadget before submitting a rental request.',
                ]);
            }

            $platformFeePercentage = (float) PlatformSetting::query()
                ->findOrFail(1)
                ->platform_fee_percentage;

            foreach ($cartItems as $cartItem) {
                $gadget = Gadget::query()
                    ->whereKey($cartItem->gadget_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($gadget->status !== 'Available') {
                    throw ValidationException::withMessages([
                        'cart' => "{$gadget->name} is no longer available for rental requests.",
                    ]);
                }

                if ($gadget->rental_payment === null || (float) $gadget->rental_payment <= 0) {
                    throw ValidationException::withMessages([
                        'cart' => "{$gadget->name} does not have a valid rental rate.",
                    ]);
                }

                if ($gadget->lender_id === $customer->id) {
                    throw ValidationException::withMessages([
                        'cart' => 'You cannot submit a rental request for a gadget you own.',
                    ]);
                }

                if ($gadget->rental_duration !== null && $rentalDays < $gadget->rental_duration) {
                    throw ValidationException::withMessages([
                        'end_date' => "{$gadget->name} requires a minimum rental of {$gadget->rental_duration} days.",
                    ]);
                }

                if (
                    ($gadget->availability_date !== null && $startDate->lt($gadget->availability_date))
                    || ($gadget->availability_end_date !== null && $endDate->gt($gadget->availability_end_date))
                ) {
                    throw ValidationException::withMessages([
                        'start_date' => "{$gadget->name} is not available for the selected dates.",
                    ]);
                }

                $hasOverlappingRental = RentalTransaction::query()
                    ->where('gadget_id', $gadget->id)
                    ->where('status', 'Active')
                    ->whereDate('start_date', '<', $endDate->toDateString())
                    ->whereDate('end_date', '>', $startDate->toDateString())
                    ->exists();

                if ($hasOverlappingRental) {
                    throw ValidationException::withMessages([
                        'start_date' => "{$gadget->name} already has a rental during the selected dates.",
                    ]);
                }

                $dailyRate = (float) $gadget->rental_payment;
                $totalAmount = round($dailyRate * $rentalDays, 2);

                RentalTransaction::create([
                    'lender_id' => $gadget->lender_id,
                    'renter_id' => $customer->id,
                    'gadget_id' => $gadget->id,
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'daily_rate' => $dailyRate,
                    'total_amount' => $totalAmount,
                    'platform_fee' => round($totalAmount * $platformFeePercentage / 100, 2),
                    'status' => 'Pending',
                ]);
            }

            CartItem::query()->whereIn('id', $cartItems->modelKeys())->delete();

            return $cartItems->count();
        });

        return redirect()
            ->route('myrentals', ['status' => 'pending'])
            ->with('status', "{$createdCount} rental request(s) submitted for lender approval. No payment was taken.");
    }

    public function store(Request $request, Gadget $gadget): RedirectResponse
    {
        $customer = $this->customer($request);
        abort_unless($gadget->status === 'Available', 404);
        abort_if($gadget->lender_id === $customer->id, 403);

        $cartItem = $customer->cartItems()->firstOrCreate([
            'gadget_id' => $gadget->id,
        ]);

        return redirect()->route('customer.cart')->with(
            'status',
            $cartItem->wasRecentlyCreated
                ? 'Added to your cart.'
                : 'This gadget is already in your cart.',
        );
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $customer = $this->customer($request);
        $customer->cartItems()->whereKey($cartItem->getKey())->firstOrFail()->delete();

        return redirect()->route('customer.cart')->with('status', 'Removed from your cart.');
    }

    private function customer(Request $request): User
    {
        $customer = $request->user();
        abort_unless($customer instanceof User && $customer->role === 'user', 403);

        return $customer;
    }
}
