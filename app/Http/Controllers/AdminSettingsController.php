<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->role === 'admin', 403);

        return view('Admin_Page.settings', [
            'user' => $request->user(),
            'platformSetting' => PlatformSetting::query()->findOrFail(1),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user?->role === 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->fill($validated)->save();

        return back()->with('status', 'Admin profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user?->role === 'admin', 403);

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('status', 'Admin password updated.');
    }

    public function updatePlatform(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'platform_fee_percentage' => ['required', 'numeric', 'between:0,100', 'decimal:0,2'],
        ]);

        PlatformSetting::query()->findOrFail(1)->update($validated);

        return back()->with('status', 'Platform settings updated.');
    }
}
