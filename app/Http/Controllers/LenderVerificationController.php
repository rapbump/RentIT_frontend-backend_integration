<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class LenderVerificationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);

        if ($lender->seller_verification_submitted_at !== null) {
            return redirect()->route('verification.message');
        }

        return view('verification pages.Seller_Verify', compact('lender'));
    }

    public function store(Request $request): RedirectResponse
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);
        abort_if($lender->seller_verification_submitted_at !== null, 403);

        $validated = $request->validate([
            'official_name' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'barangay' => ['required', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:500'],
            'government_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'residence_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'barangay_certification' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        $storedPaths = [];

        try {
            foreach ([
                'government_id' => 'government_id_path',
                'residence_proof' => 'residence_proof_path',
                'barangay_certification' => 'barangay_certification_path',
            ] as $input => $attribute) {
                if (! $request->hasFile($input)) {
                    continue;
                }

                $path = $request->file($input)->store('lender-verification', 'local');

                if ($path === false) {
                    throw new RuntimeException("The {$input} document could not be stored.");
                }

                $storedPaths[] = $path;
                $validated[$attribute] = $path;
            }

            $lender->verification_official_name = $validated['official_name'];
            $lender->verification_province = $validated['province'];
            $lender->verification_city = $validated['city'];
            $lender->verification_barangay = $validated['barangay'];
            $lender->verification_street_address = $validated['street_address'];
            $lender->government_id_path = $validated['government_id_path'];
            $lender->residence_proof_path = $validated['residence_proof_path'];
            $lender->barangay_certification_path = $validated['barangay_certification_path'] ?? null;
            $lender->seller_verification_status = 'Pending';
            $lender->seller_verification_submitted_at = now();
            $lender->save();
        } catch (Throwable $exception) {
            if ($storedPaths !== [] && ! Storage::disk('local')->delete($storedPaths)) {
                throw new RuntimeException(
                    'Uploaded verification documents could not be cleaned up after submission failed.',
                    previous: $exception,
                );
            }

            throw $exception;
        }

        return redirect()
            ->route('verification.message')
            ->with('status', 'Your verification requirements were submitted successfully.');
    }
}
