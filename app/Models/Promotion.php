<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_fare',
        'maximum_discount',
        'max_uses',
        'used_count',
        'max_uses_per_user',
        'starts_at',
        'expires_at',
        'is_active',
        'image_url',
    ];

    protected $casts = [
        'discount_value'   => 'decimal:2',
        'minimum_fare'     => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'starts_at'        => 'datetime',
        'expires_at'       => 'datetime',
        'is_active'        => 'boolean',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // ------------------------------------------------------------------
    // BUSINESS LOGIC
    // ------------------------------------------------------------------

    /**
     * Calculate the discount amount for a given fare.
     */
    public function calculateDiscount(float $fare): float
    {
        if ($this->minimum_fare && $fare < $this->minimum_fare) {
            return 0.0;
        }

        $discount = $this->discount_type === 'percent'
            ? $fare * ($this->discount_value / 100)
            : (float) $this->discount_value;

        // Apply cap for percent discounts
        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = (float) $this->maximum_discount;
        }

        return round(min($discount, $fare), 2);
    }

    /**
     * Check if this promo is currently usable.
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }
        if ($this->expires_at && now()->gt($this->expires_at)) {
            return false;
        }
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Check if a specific user has exceeded their usage limit.
     */
    public function isValidForUser(int $userId): bool
    {
        if (! $this->isValid()) {
            return false;
        }

        $userUsageCount = $this->bookings()
            ->where('user_id', $userId)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        return $userUsageCount < $this->max_uses_per_user;
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
    }
}