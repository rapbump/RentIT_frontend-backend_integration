<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VerificationDocumentsController extends Controller
{
    public function download(Request $request, User $seller, string $document): StreamedResponse
    {
        abort_unless($request->user()?->role === 'admin', 403);
        abort_unless($seller->role === 'lender', 404);

        $attribute = match ($document) {
            'government-id' => 'government_id_path',
            'residence-proof' => 'residence_proof_path',
            'barangay-certification' => 'barangay_certification_path',
            default => null,
        };

        abort_if($attribute === null, 404);
        $path = $seller->{$attribute};

        abort_unless(
            $seller->seller_verification_submitted_at !== null
                && is_string($path)
                && Storage::disk('local')->exists($path),
            404,
        );

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return Storage::disk('local')->download(
            $path,
            "{$document}-{$seller->id}.{$extension}",
        );
    }
}
