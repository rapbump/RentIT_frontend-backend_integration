<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LenderDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);

        $gadgets = $lender->gadgets();
        $weekStart = now()->startOfWeek();

        return view('Seller-Page.Dashboard', [
            'activeGadgetsCount' => (clone $gadgets)->where('status', 'Available')->count(),
            'gadgetsListedThisWeekCount' => (clone $gadgets)->where('created_at', '>=', $weekStart)->count(),
            'recentGadgets' => (clone $gadgets)->latest()->orderByDesc('id')->limit(5)->get(),
        ]);
    }
}
