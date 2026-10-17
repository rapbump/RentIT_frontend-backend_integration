<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'role',
        'avatar_path',
        'location',
        'store_name',
        'store_about',
        'operating_days',
        'business_hours',
        'meetup_locations',
    ];

    public function gadgets()
    {
        return $this->hasMany(Gadget::class, 'lender_id');
    }

    public function rentalTransactions(): HasMany
    {
        return $this->hasMany(RentalTransaction::class, 'lender_id');
    }
}
