<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class GadgetController extends Controller
{
    public function index(): View
    {
        $this->ensureLender();

        $gadgets = Gadget::where('lender_id', Auth::id())
            ->latest()
            ->get();

        return view('Seller-Page.gadgets', compact('gadgets'));
    }

    public function create(): View
    {
        $this->ensureLender();

        return view('Seller-Page.add-gadget');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureLender();

        $validated = $request->validate([
            'action' => ['required', Rule::in(['draft', 'publish'])],
            'name' => ['required', 'string', 'max:255'],
            'model' => ['nullable', 'required_if:action,publish', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(['Smartphone', 'Tablet', 'Laptop', 'Camera'])],
            'brand' => ['nullable', 'required_if:action,publish', 'string', 'max:255'],
            'condition' => ['nullable', 'required_if:action,publish', Rule::in(['Excellent', 'Very Good', 'Good', 'Fair'])],
            'description' => ['nullable', 'required_if:action,publish', 'string', 'max:5000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'additional_photos' => ['nullable', 'array', 'max:3'],
            'additional_photos.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'daily_rate' => ['nullable', 'required_if:action,publish', 'numeric', 'min:0.01', 'decimal:0,2'],
            'weekly_rate' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'monthly_rate' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'security_deposit' => ['nullable', 'required_if:action,publish', 'numeric', 'min:0', 'decimal:0,2'],
            'rental_duration' => ['nullable', 'required_if:action,publish', 'integer', Rule::in([1, 2, 3, 7])],
            'cancellation_policy' => ['nullable', 'required_if:action,publish', Rule::in(['Flexible', 'Moderate', 'Strict'])],
            'pickup_mode' => ['nullable', 'required_if:action,publish', Rule::in(['Meetup', 'Doorstep delivery', 'Pickup only'])],
            'delivery_fee' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'availability_date' => ['nullable', 'required_if:action,publish', 'date'],
            'availability_end_date' => ['nullable', 'date', 'after_or_equal:availability_date'],
        ]);

        $storedPaths = [];

        try {
            $gadget = DB::transaction(function () use ($request, $validated, &$storedPaths): Gadget {
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('gadgets', 'public');
                    if ($photoPath === false) {
                        throw new \RuntimeException('The main gadget image could not be stored.');
                    }

                    $storedPaths[] = $photoPath;
                }

                $galleryPaths = [];
                foreach (array_filter($request->file('additional_photos', [])) as $photo) {
                    $galleryPath = $photo->store('gadgets', 'public');
                    if ($galleryPath === false) {
                        throw new \RuntimeException('A gadget image could not be stored.');
                    }

                    $galleryPaths[] = $galleryPath;
                    $storedPaths[] = $galleryPath;
                }

                return Gadget::create([
                    'lender_id' => $request->user()->id,
                    'name' => $validated['name'],
                    'model' => $validated['model'] ?? null,
                    'category' => $validated['category'],
                    'brand' => $validated['brand'] ?? null,
                    'condition' => $validated['condition'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'photo_path' => $photoPath,
                    'gallery_paths' => $galleryPaths,
                    'rental_duration' => $validated['rental_duration'] ?? null,
                    'security_deposit' => $validated['security_deposit'] ?? null,
                    'rental_payment' => $validated['daily_rate'] ?? null,
                    'weekly_rate' => $validated['weekly_rate'] ?? null,
                    'monthly_rate' => $validated['monthly_rate'] ?? null,
                    'cancellation_policy' => $validated['cancellation_policy'] ?? null,
                    'availability_date' => $validated['availability_date'] ?? null,
                    'availability_end_date' => $validated['availability_end_date'] ?? null,
                    'status' => $validated['action'] === 'publish' ? 'Available' : 'Draft',
                    'meetup_options' => isset($validated['pickup_mode']) ? [$validated['pickup_mode']] : [],
                    'pickup_mode' => $validated['pickup_mode'] ?? null,
                    'delivery_fee' => $validated['delivery_fee'] ?? null,
                ]);
            });
        } catch (Throwable $exception) {
            if ($storedPaths !== [] && ! Storage::disk('public')->delete($storedPaths)) {
                throw new \RuntimeException('Failed to clean up gadget images after the listing could not be saved.', previous: $exception);
            }

            throw $exception;
        }

        return redirect()
            ->route('lender.gadgets')
            ->with('success', $gadget->status === 'Draft' ? 'Gadget draft saved.' : 'Gadget published successfully.');
    }

    public function show(Gadget $gadget): View
    {
        $this->ensureLender();
        abort_unless($gadget->lender_id === Auth::id(), 404);

        return view('gadgets.show', compact('gadget'));
    }

    private function ensureLender(): void
    {
        abort_unless(Auth::user()?->role === 'lender', 403);
    }
}
