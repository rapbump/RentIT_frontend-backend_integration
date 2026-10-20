<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerPageController extends Controller
{
    public function showGadget(Request $request, Gadget $gadget): View
    {
        abort_unless($gadget->status === 'Available', 404);

        $gadget->load('lender:id,name,store_name,location,meetup_locations');

        $galleryImages = collect([$gadget->photo_path, ...($gadget->gallery_paths ?? [])])
            ->filter()
            ->unique()
            ->map(fn (string $path): string => Storage::disk('public')->url($path))
            ->values();
        $cartedGadgetIds = $request->user()?->role === 'user'
            ? $request->user()->cartItems()->pluck('gadget_id')
            : collect();

        return view('Customer_Page.details', [
            'gadget' => $gadget,
            'customer' => $request->user(),
            'cartCount' => $cartedGadgetIds->count(),
            'isInCart' => $cartedGadgetIds->contains($gadget->id),
            'galleryImages' => $galleryImages,
            'mainImage' => $galleryImages->first() ?? asset('images/cp.png'),
        ]);
    }

    public function myRentals(Request $request): View
    {
        $customer = $request->user();
        abort_unless($customer?->role === 'user', 403);

        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['all', 'pending', 'active', 'completed', 'disputed', 'rejected'])],
        ]);

        $status = $validated['status'] ?? 'all';
        $rentals = $customer->rentedTransactions()
            ->with([
                'gadget:id,name,photo_path',
                'lender:id,name,store_name',
            ])
            ->when($status !== 'all', fn ($query) => $query->where('status', ucfirst($status)))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('Customer_Page.my_rentals', [
            'customer' => $customer,
            'rentals' => $rentals,
            'status' => $status,
            'rentalCount' => $customer->rentedTransactions()->count(),
            'avatarUrl' => $customer->avatar_path
                ? Storage::disk('public')->url($customer->avatar_path)
                : asset('images/76e11a76-2275-47ba-83c3-40969564e34d.jpg'),
        ]);
    }
}
