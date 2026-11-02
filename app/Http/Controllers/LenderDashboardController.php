<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LenderDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);

        $gadgets = $lender->gadgets();
        $weekStart = now()->startOfWeek();

        return view('Seller-Page.Dashboard', [
            'lender' => $lender,
            'activeGadgetsCount' => (clone $gadgets)->where('status', 'Available')->count(),
            'gadgetsListedThisWeekCount' => (clone $gadgets)->where('created_at', '>=', $weekStart)->count(),
            'pendingRequestsCount' => $lender->rentalTransactions()->where('status', 'Pending')->count(),
            'recentGadgets' => (clone $gadgets)->latest()->orderByDesc('id')->limit(5)->get(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);
        $platformFeePercentage = (float) PlatformSetting::query()
            ->findOrFail(1)
            ->platform_fee_percentage;

        return response()->streamDownload(function () use ($lender, $platformFeePercentage): void {
            $output = fopen('php://output', 'wb');

            if ($output === false) {
                throw new \RuntimeException('The earnings report could not be opened for writing.');
            }

            if (fputcsv($output, [
                'Rental ID',
                'Gadget',
                'Renter',
                'Start Date',
                'End Date',
                'Status',
                'Daily Rate',
                'Rental Total',
                'Platform Fee',
                'Estimated Payout',
            ], ',', '"', '') === false) {
                throw new \RuntimeException('The earnings report headers could not be written.');
            }

            $lender->rentalTransactions()
                ->whereIn('status', ['Active', 'Completed'])
                ->with(['renter:id,name', 'gadget:id,name'])
                ->orderBy('id')
                ->lazy(100)
                ->each(function ($rental) use ($output, $platformFeePercentage): void {
                    $total = (float) $rental->total_amount;
                    $fee = round($total * $platformFeePercentage / 100, 2);

                    if (fputcsv($output, [
                        $rental->id,
                        $rental->gadget?->name ?? 'Deleted gadget',
                        $rental->renter?->name ?? 'Deleted renter',
                        $rental->start_date->toDateString(),
                        $rental->end_date->toDateString(),
                        $rental->status,
                        number_format((float) $rental->daily_rate, 2, '.', ''),
                        number_format($total, 2, '.', ''),
                        number_format($fee, 2, '.', ''),
                        number_format($total - $fee, 2, '.', ''),
                    ], ',', '"', '') === false) {
                        throw new \RuntimeException('An earnings report row could not be written.');
                    }
                });

            fclose($output);
        }, 'rentit-earnings-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
