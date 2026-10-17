<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
