<?php

namespace App\Http\Controllers;

use App\Models\RentalTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminRentalController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['all', 'pending', 'active', 'completed', 'disputed', 'rejected'])],
        ]);

        $status = $validated['status'] ?? 'all';

        $rentals = RentalTransaction::query()
            ->with([
                'renter:id,name',
                'lender:id,name,store_name',
                'gadget:id,name',
            ])
            ->when($status !== 'all', fn ($query) => $query->where('status', ucfirst($status)))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('Admin_Page.rentals', compact('rentals', 'status'));
    }
}
