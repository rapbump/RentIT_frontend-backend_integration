<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLenderIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->role !== 'lender') {
            return $next($request);
        }

        if ($user->seller_verification_status === 'Approved') {
            if ($request->routeIs('verification.message')) {
                return redirect()->route('lender.dashboard');
            }

            return $next($request);
        }

        if ($request->routeIs('logout')) {
            return $next($request);
        }

        if ($user->seller_verification_submitted_at === null) {
            if ($request->routeIs('seller.verify', 'seller.verify.submit')) {
                return $next($request);
            }

            return redirect()->route('seller.verify');
        }

        if ($request->routeIs('verification.message')) {
            return $next($request);
        }

        return redirect()->route('verification.message');
    }
}
