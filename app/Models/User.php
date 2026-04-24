<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'user_type_id',
        'discount_type_id',
        'status',
        'phone',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Role Checks (fast, no DB hit) ───────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The user's role type (admin / driver / customer).
     * Use this for display purposes (display_name, description).
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * The discount category assigned to this user (senior, pwd, student, etc.).
     * NULL = no discount.
     */
    public function discountType(): BelongsTo
    {
        return $this->belongsTo(DiscountType::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    // ─── Discount / Fare Logic ────────────────────────────────────────────────

    /**
     * Apply this user's discount (if any) to a base fare.
     * Falls back to full fare if no discount type is assigned.
     */
    public function calculateFare(float $baseFare): float
    {
        return $this->discountType
            ? $this->discountType->applyDiscount($baseFare)
            : $baseFare;
    }

    /**
     * Returns the user's discount percentage as a float (0.0 – 1.0).
     * Returns 0 if no discount type is assigned.
     */
    public function getDiscountRateAttribute(): float
    {
        return (float) ($this->discountType?->percentage ?? 0);
    }

    /**
     * Returns true if this user has any discount applied.
     */
    public function hasDiscount(): bool
    {
        return $this->discount_type_id !== null;
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeWithDiscount($query)
    {
        return $query->whereNotNull('discount_type_id');
    }
}