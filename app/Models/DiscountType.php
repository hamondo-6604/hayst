<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscountType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',          // senior_citizen | pwd | student | regular
        'display_name',  // Senior Citizen | Person with Disability | Student | Regular
        'percentage',    // 0.20 = 20% off
        'description',
        'is_active',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    /**
     * Apply this discount to a base fare and return the discounted amount.
     * e.g. percentage = 0.20, baseFare = 500 → returns 400.00
     */
    public function applyDiscount(float $baseFare): float
    {
        return round($baseFare * (1 - (float) $this->percentage), 2);
    }

    /**
     * Return the discount amount (not the final fare).
     * e.g. percentage = 0.20, baseFare = 500 → returns 100.00
     */
    public function discountAmount(float $baseFare): float
    {
        return round($baseFare * (float) $this->percentage, 2);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}