<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class LenderSettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user?->role === 'lender', 403);

        return view('Seller-Page.settings', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user?->role === 'lender', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $user->fill($validated)->save();

        return back()->with('status', 'Name and destination saved.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user?->role === 'lender', 403);

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        $user->password = $validated['new_password'];
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('homepage');
    }
}
