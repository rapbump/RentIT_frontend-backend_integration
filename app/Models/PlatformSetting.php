<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'platform_fee_percentage',
    ];

    protected function casts(): array
    {
        return [
            'platform_fee_percentage' => 'decimal:2',
        ];
    }
}
