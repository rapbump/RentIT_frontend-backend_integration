<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerBrowseController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $gadgets = Gadget::query()
            ->select([
                'id',
                'lender_id',
                'name',
                'model',
                'category',
                'brand',
                'photo_path',
                'rental_payment',
                'status',
                'created_at',
            ])
            ->with('lender:id,name,store_name')
            ->where('status', 'Available')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, function ($query, string $category): void {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('Customer_Page.customer_browse', [
            'gadgets' => $gadgets,
            'customer' => $request->user(),
            'cartCount' => $request->user()?->role === 'user'
                ? $request->user()->cartItems()->count()
                : 0,
            'search' => $filters['search'] ?? '',
            'category' => $filters['category'] ?? '',
        ]);
    }
}
