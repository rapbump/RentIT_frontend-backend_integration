<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gadget extends Model
{
    protected $fillable = [
        'lender_id',
        'name',
        'model',
        'category',
        'brand',
        'condition',
        'description',
        'photo_path',
        'gallery_paths',
        'rental_duration',
        'security_deposit',
        'rental_payment',
        'weekly_rate',
        'monthly_rate',
        'cancellation_policy',
        'availability_date',
        'availability_end_date',
        'status',
        'meetup_options',
        'pickup_mode',
        'delivery_fee',
    ];

    protected function casts(): array
    {
        return [
            'gallery_paths' => 'array',
            'meetup_options' => 'array',
            'availability_date' => 'date',
            'availability_end_date' => 'date',
        ];
    }

    public function lender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function rentalTransactions(): HasMany
    {
        return $this->hasMany(RentalTransaction::class);
    }

    #[Scope]
    protected function availableForRent(Builder $query): void
    {
        $today = today()->toDateString();
        $model = $query->getModel();
        $availabilityStart = $model->qualifyColumn('availability_date');
        $availabilityEnd = $model->qualifyColumn('availability_end_date');
        $minimumRentalDays = $model->qualifyColumn('rental_duration');
        $availableDays = match ($query->getConnection()->getDriverName()) {
            'sqlite' => "julianday({$availabilityEnd}) - julianday({$availabilityStart})",
            'mysql' => "DATEDIFF({$availabilityEnd}, {$availabilityStart})",
            default => throw new \LogicException('The availability scope requires a MySQL or SQLite connection.'),
        };

        $query->where('status', 'Available')
            ->whereDoesntHave('rentalTransactions', fn (Builder $rentalQuery) => $rentalQuery
                ->where(function (Builder $statusQuery) use ($today): void {
                    $statusQuery->where('status', 'Pending')
                        ->orWhere(function (Builder $activeQuery) use ($today): void {
                            $activeQuery->where('status', 'Active')
                                ->whereDate('start_date', '<=', $today)
                                ->whereDate('end_date', '>', $today);
                        });
                }))
            ->where(function (Builder $availabilityQuery) use ($availabilityStart, $availabilityEnd, $minimumRentalDays, $availableDays): void {
                $availabilityQuery->whereNull($availabilityStart)
                    ->orWhereNull($availabilityEnd)
                    ->orWhereNull($minimumRentalDays)
                    ->orWhereRaw("{$availableDays} >= {$minimumRentalDays}");
            });
    }
}
