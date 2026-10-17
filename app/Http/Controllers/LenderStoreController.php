<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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

        return view('Seller-Page.myStore', compact('featuredGadgets'));
    }
}
