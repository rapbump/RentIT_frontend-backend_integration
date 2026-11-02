<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LenderRentersController extends Controller
{
    public function __invoke(Request $request): View
    {
        $lender = $request->user();
        abort_unless($lender?->role === 'lender', 403);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['all', 'active', 'pending', 'completed', 'disputed', 'rejected'])],
            'renter' => ['nullable', 'integer'],
        ]);

        $renterScope = static fn (): Builder => User::query()
            ->where('role', 'user')
            ->whereHas('rentedTransactions', fn (Builder $query) => $query->where('lender_id', $lender->id));

        $aggregateQuery = static fn (): Builder => $renterScope()
            ->withCount([
                'rentedTransactions as rental_count' => fn (Builder $query) => $query->where('lender_id', $lender->id),
                'rentedTransactions as active_rentals_count' => fn (Builder $query) => $query->where('lender_id', $lender->id)->where('status', 'Active'),
                'rentedTransactions as pending_rentals_count' => fn (Builder $query) => $query->where('lender_id', $lender->id)->where('status', 'Pending'),
                'rentedTransactions as completed_rentals_count' => fn (Builder $query) => $query->where('lender_id', $lender->id)->where('status', 'Completed'),
                'rentedTransactions as disputes_count' => fn (Builder $query) => $query->where('lender_id', $lender->id)->where('status', 'Disputed'),
            ])
            ->withSum([
                'rentedTransactions as rental_value' => fn (Builder $query) => $query
                    ->where('lender_id', $lender->id)
                    ->whereIn('status', ['Active', 'Completed']),
            ], 'total_amount');

        $statusCounts = [
            'totalRentersCount' => $renterScope()->count(),
            'activeRentersCount' => $renterScope()
                ->whereHas('rentedTransactions', fn (Builder $query) => $query->where('lender_id', $lender->id)->where('status', 'Active'))
                ->count(),
            'returningRentersCount' => $renterScope()
                ->whereHas('rentedTransactions', fn (Builder $query) => $query->where('lender_id', $lender->id)->where('status', 'Completed'))
                ->count(),
        ];

        $renters = $aggregateQuery()
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when(($filters['status'] ?? 'all') !== 'all', function (Builder $query) use ($filters, $lender): void {
                $query->whereHas('rentedTransactions', fn (Builder $transactionQuery) => $transactionQuery
                    ->where('lender_id', $lender->id)
                    ->where('status', ucfirst($filters['status'])));
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $renters->getCollection()->each(fn (User $renter) => $this->setDisplayStatus($renter));

        $selectedRenter = isset($filters['renter'])
            ? $aggregateQuery()->whereKey($filters['renter'])->firstOrFail()
            : $renters->first();

        if ($selectedRenter !== null) {
            $this->setDisplayStatus($selectedRenter);
            $selectedRenter->setAttribute(
                'rental_history',
                $selectedRenter->rentedTransactions()
                    ->where('lender_id', $lender->id)
                    ->with('gadget:id,name')
                    ->orderByDesc('start_date')
                    ->orderByDesc('id')
                    ->get(),
            );
        }

        return view('Seller-Page.renters', [
            'lender' => $lender,
            'renters' => $renters,
            'selectedRenter' => $selectedRenter,
            'search' => $filters['search'] ?? '',
            'status' => $filters['status'] ?? 'all',
            ...$statusCounts,
        ]);
    }

    private function setDisplayStatus(User $renter): void
    {
        foreach (['active', 'pending', 'completed', 'disputes'] as $status) {
            $countAttribute = $status === 'disputes' ? 'disputes_count' : "{$status}_rentals_count";

            if ($renter->{$countAttribute} > 0) {
                $renter->setAttribute('status', $status === 'disputes' ? 'disputed' : $status);

                return;
            }
        }

        $renter->setAttribute('status', 'rejected');
    }
}
