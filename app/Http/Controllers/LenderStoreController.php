<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class LenderStoreController extends Controller
{
    public function __invoke(Request $request): View
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);

        $featuredGadgets = $lender->gadgets()
            ->where('status', 'Available')
            ->select([
                'id',
                'lender_id',
                'name',
                'category',
                'condition',
                'photo_path',
                'rental_payment',
                'created_at',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(4)
            ->get()
            ->map(function (Gadget $gadget): Gadget {
                $gadget->setAttribute(
                    'image_url',
                    $gadget->photo_path
                        ? Storage::disk('public')->url($gadget->photo_path)
                        : asset('images/no_image.png')
                );

                return $gadget;
            });

        $avatarUrl = $lender->avatar_path
            ? Storage::disk('public')->url($lender->avatar_path)
            : asset('images/prof.png');
        $backgroundUrl = $lender->background_path
            ? Storage::disk('public')->url($lender->background_path)
            : asset('images/bg.png');

        return view('Seller-Page.myStore', compact('lender', 'featuredGadgets', 'avatarUrl', 'backgroundUrl'));
    }

    public function updateStoreProfile(Request $request): RedirectResponse
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($lender->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'store_name' => ['nullable', 'string', 'max:255'],
            'store_about' => ['nullable', 'string', 'max:2000'],
            'location' => ['nullable', 'string', 'max:255'],
            'operating_days' => ['nullable', 'string', 'max:255'],
            'business_hours' => ['nullable', 'string', 'max:255'],
            'meetup_locations' => ['nullable', 'string', 'max:2000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'background' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $storedPaths = [];
        $previousPaths = [];

        try {
            foreach (['avatar' => 'avatar_path', 'background' => 'background_path'] as $input => $attribute) {
                if (! $request->hasFile($input)) {
                    continue;
                }

                $path = $request->file($input)->store('lender-profiles', 'public');

                if ($path === false) {
                    throw new RuntimeException("The {$input} image could not be stored.");
                }

                $storedPaths[] = $path;
                $previousPaths[] = $lender->{$attribute};
                $validated[$attribute] = $path;
            }

            unset($validated['avatar'], $validated['background']);
            $lender->fill($validated)->save();
        } catch (Throwable $exception) {
            if ($storedPaths !== [] && ! Storage::disk('public')->delete($storedPaths)) {
                throw new RuntimeException('Failed to clean up uploaded store images after the profile could not be saved.', previous: $exception);
            }

            throw $exception;
        }

        $previousPaths = array_filter($previousPaths);

        if ($previousPaths !== [] && ! Storage::disk('public')->delete($previousPaths)) {
            throw new RuntimeException('The store profile was updated, but an old profile image could not be removed.');
        }

        return back()->with('status', 'Store profile and information updated.');
    }
}
