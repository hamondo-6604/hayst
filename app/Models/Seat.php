<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'seat_number',
        'seat_type',        // string label kept for BC — proper FK below
        'seat_type_id',     // NEW: FK to seat_types table
        'status',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Proper FK relationship to seat_types.
     * If seat_type_id is null, fall back to the string seat_type column.
     */
    public function effectiveSeatType(): BelongsTo
    {
        return $this->belongsTo(SeatType::class, 'seat_type_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingSeats(): HasMany
    {
        return $this->hasMany(BookingSeat::class);
    }

    // ------------------------------------------------------------------
    // ACCESSORS
    // ------------------------------------------------------------------

    /**
     * Returns the SeatType model if linked, otherwise null.
     * Resolves via seat_type_id first, then falls back to name lookup on seat_type string.
     */
    public function getResolvedSeatTypeAttribute(): ?SeatType
    {
        if ($this->seat_type_id) {
            return $this->effectiveSeatType;
        }

        $typeName = $this->seat_type ?? $this->bus?->default_seat_type ?? 'economy';
        return SeatType::where('name', $typeName)->first();
    }

    /**
     * String name of the effective seat type for display.
     */
    public function getEffectiveSeatTypeAttribute(): string
    {
        return $this->resolvedSeatType?->name
            ?? $this->seat_type
            ?? $this->bus?->default_seat_type
            ?? 'economy';
    }

    /**
     * Calculate the fare for this seat given a base trip fare.
     * Uses the SeatType price_multiplier if available.
     */
    public function calculateFare(float $baseFare): float
    {
        return $this->resolvedSeatType
            ? $this->resolvedSeatType->calculateFare($baseFare)
            : $baseFare;
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('seat_type', $type);
    }

    public function scopeByTypeId($query, int $seatTypeId)
    {
        return $query->where('seat_type_id', $seatTypeId);
    }
}