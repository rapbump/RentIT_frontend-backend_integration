<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            'activeRentalRevenue' => $activeRentalRevenue,
            'platformFee' => $platformFee,
            'estimatedPayout' => $activeRentalRevenue - $platformFee,
            'completedDealsCount' => $completedDealsCount,
            'disputedTransactionsCount' => $disputedTransactionsCount,
            'month' => $month,
        ]);
    }
}
