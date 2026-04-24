<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'price_multiplier',
        'icon',
        'color_hex',
        'is_active',
    ];

    protected $casts = [
        'price_multiplier' => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function layoutMaps(): HasMany
    {
        return $this->hasMany(LayoutMap::class);
    }

    public function bookingSeats(): HasMany
    {
        return $this->hasMany(BookingSeat::class);
    }

    // ------------------------------------------------------------------
    // BUSINESS LOGIC
    // ------------------------------------------------------------------

    /**
     * Calculate the fare for this seat type given a base trip fare.
     */
    public function calculateFare(float $baseFare): float
    {
        return round($baseFare * (float) $this->price_multiplier, 2);
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}