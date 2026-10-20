<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LenderTransactionController extends Controller
{
    public function __invoke(Request $request): View
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);

        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $month = $validated['month'] ?? now()->format('Y-m');
        $monthStart = Carbon::parse($month.'-01')->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $activeRentalsQuery = $lender->rentalTransactions()
            ->where('status', 'Active')
            ->whereDate('start_date', '<=', $monthEnd->toDateString())
            ->whereDate('end_date', '>=', $monthStart->toDateString());

        $activeRentals = (clone $activeRentalsQuery)
            ->with(['renter:id,name', 'gadget:id,name'])
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $pendingRequests = $lender->rentalTransactions()
            ->where('status', 'Pending')
            ->with(['renter:id,name', 'gadget:id,name'])
            ->orderBy('start_date')
            ->orderBy('id')
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        $activeRentalRevenue = (clone $activeRentalsQuery)->sum('total_amount');
        $platformFee = (clone $activeRentalsQuery)->sum('platform_fee');
        $completedDealsCount = $lender->rentalTransactions()
            ->where('status', 'Completed')
            ->whereBetween('start_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->count();
        $disputedTransactionsCount = $lender->rentalTransactions()
            ->where('status', 'Disputed')
            ->whereBetween('start_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->count();

        return view('Seller-Page.transaction', [
            'activeRentals' => $activeRentals,
            'pendingRequests' => $pendingRequests,
            'activeRentalRevenue' => $activeRentalRevenue,
            'platformFee' => $platformFee,
            'estimatedPayout' => $activeRentalRevenue - $platformFee,
            'completedDealsCount' => $completedDealsCount,
            'disputedTransactionsCount' => $disputedTransactionsCount,
            'month' => $month,
        ]);
    }

    public function approveRequest(Request $request, RentalTransaction $rental): RedirectResponse
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);
        $month = $rental->start_date->format('Y-m');

        DB::transaction(function () use ($lender, $rental): void {
            $pendingRequest = $lender->rentalTransactions()
                ->whereKey($rental->id)
                ->where('status', 'Pending')
                ->lockForUpdate()
                ->firstOrFail();
            $gadget = Gadget::query()
                ->whereKey($pendingRequest->gadget_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($gadget->status !== 'Available' || $pendingRequest->start_date->isBefore(today())) {
                throw ValidationException::withMessages([
                    'request' => 'This request can no longer be approved because the gadget or rental dates are unavailable.',
                ]);
            }

            $hasOverlappingRental = RentalTransaction::query()
                ->where('gadget_id', $gadget->id)
                ->where('status', 'Active')
                ->where('id', '!=', $pendingRequest->id)
                ->whereDate('start_date', '<', $pendingRequest->end_date->toDateString())
                ->whereDate('end_date', '>', $pendingRequest->start_date->toDateString())
                ->exists();

            if ($hasOverlappingRental) {
                throw ValidationException::withMessages([
                    'request' => 'This request overlaps an approved rental and cannot be approved.',
                ]);
            }

            $pendingRequest->update(['status' => 'Active']);
        });

        return redirect()
            ->route('lender.transaction', ['month' => $month])
            ->with('status', 'Rental request approved.');
    }

    public function rejectRequest(Request $request, RentalTransaction $rental): RedirectResponse
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);
        $month = $rental->start_date->format('Y-m');

        DB::transaction(function () use ($lender, $rental): void {
            $lender->rentalTransactions()
                ->whereKey($rental->id)
                ->where('status', 'Pending')
                ->lockForUpdate()
                ->firstOrFail()
                ->update(['status' => 'Rejected']);
        });

        return redirect()
            ->route('lender.transaction', ['month' => $month])
            ->with('status', 'Rental request rejected.');
    }
}
