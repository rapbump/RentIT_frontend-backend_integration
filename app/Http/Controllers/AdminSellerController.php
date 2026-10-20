<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSellerController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['all', 'pending', 'approved', 'rejected'])],
        ]);

        $status = $validated['status'] ?? 'all';
        $sellerStatus = ucfirst($status);

        $sellers = User::query()
            ->where('role', 'lender')
            ->when($status !== 'all', function (Builder $query) use ($sellerStatus): void {
                if ($sellerStatus === 'Pending') {
                    $query->where(function (Builder $query): void {
                        $query->whereNull('seller_verification_status')
                            ->orWhere('seller_verification_status', 'Pending');
                    });

                    return;
                }

                $query->where('seller_verification_status', $sellerStatus);
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('Admin_Page.seller-and-stores', compact('sellers', 'status'));
    }

    public function updateVerificationStatus(Request $request, User $seller): RedirectResponse
    {
        abort_unless($request->user()?->role === 'admin', 403);
        abort_unless($seller->role === 'lender', 404);

        $validated = $request->validate([
            'verification_status' => ['required', Rule::in(['Pending', 'Approved', 'Rejected'])],
        ]);

        $seller->seller_verification_status = $validated['verification_status'];
        $seller->save();

        return redirect()
            ->route('admin.stores', ['status' => strtolower($validated['verification_status'])])
            ->with('status', 'Seller verification status updated.');
    }
}
