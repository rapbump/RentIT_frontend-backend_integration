<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = Validator::make($request->only('email', 'password'), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ])->validateWithBag('login');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user?->role === 'lender' && $user->seller_verification_status !== 'Approved') {
                return $user->seller_verification_submitted_at === null
                    ? redirect()->route('seller.verify')
                    : redirect()->route('verification.message');
            }

            if ($user?->role === 'user') {
                return redirect()->route('customer.browse');
            }
            if ($user?->role === 'lender') {
                return redirect()->route('lender.dashboard');
            }
            if ($user?->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }

        return redirect()->back()
            ->withErrors(['email' => 'The email or password you entered is incorrect.'], 'login')
            ->withInput($request->only('email'));
    }
}
