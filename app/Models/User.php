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
        'background_path',
        'location',
        'store_name',
        'store_about',
        'operating_days',
        'business_hours',
        'meetup_locations',
        'verification_official_name',
        'verification_province',
        'verification_city',
        'verification_barangay',
        'verification_street_address',
        'government_id_path',
        'residence_proof_path',
        'barangay_certification_path',
        'seller_verification_submitted_at',
        'seller_verification_submitted_at' => 'datetime',
    ];

    public function gadgets()
    {
        return $this->hasMany(Gadget::class, 'lender_id');
    }

    public function rentalTransactions(): HasMany
    {
        return $this->hasMany(RentalTransaction::class, 'lender_id');
    }

    public function rentedTransactions(): HasMany
    {
        return $this->hasMany(RentalTransaction::class, 'renter_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
