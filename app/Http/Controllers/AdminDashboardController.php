<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $platformFees = RentalTransaction::query()->sum('platform_fee');

        $recentListings = Gadget::query()
            ->with('lender:id,name,store_name')
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (Gadget $gadget): array => [
                'actor' => $gadget->lender->store_name ?: $gadget->lender->name,
                'verb' => 'listed',
                'subject' => $gadget->name,
                'createdAt' => $gadget->created_at,
                'icon' => 'checkmark-circle-outline',
            ]);

        $recentRentals = RentalTransaction::query()
            ->with(['renter:id,name', 'gadget:id,name'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (RentalTransaction $rental): array => [
                'actor' => $rental->renter->name,
                'verb' => 'rented',
                'subject' => $rental->gadget->name,
                'createdAt' => $rental->created_at,
                'icon' => 'document-text-outline',
            ]);

        $metrics = [
            [
                'key' => 'stores',
                'label' => 'Total Stores',
                'value' => User::query()->where('role', 'lender')->count(),
                'detail' => 'Registered lenders',
                'icon' => 'bag-handle-outline',
            ],
            [
                'key' => 'renters',
                'label' => 'Total Renters',
                'value' => User::query()->where('role', 'user')->count(),
                'detail' => 'Registered renter accounts',
                'icon' => 'people-outline',
            ],
            [
                'key' => 'gadgets',
                'label' => 'Total Gadgets',
                'value' => Gadget::query()->count(),
                'detail' => Gadget::query()->where('status', 'Available')->count().' available for rent',
                'icon' => 'phone-portrait-outline',
            ],
            [
                'key' => 'rentals',
                'label' => 'Total Rentals',
                'value' => RentalTransaction::query()->count(),
                'detail' => 'All rental transactions',
                'icon' => 'document-text-outline',
            ],
            [
                'key' => 'active-rentals',
                'label' => 'Active Rentals',
                'value' => RentalTransaction::query()->where('status', 'Active')->count(),
                'detail' => 'Currently active transactions',
                'icon' => 'hourglass-outline',
            ],
            [
                'key' => 'platform-fees',
                'label' => 'Platform Fees',
                'value' => '₱'.number_format((float) $platformFees, 2),
                'detail' => 'Total fees recorded across rentals',
                'icon' => 'cash-outline',
            ],
        ];

        $recentActivity = $recentListings
            ->concat($recentRentals)
            ->sortByDesc('createdAt')
            ->take(3)
            ->values();

        return view('Admin_Page.admin_dashboard', compact('metrics', 'recentActivity'));
    }
}
