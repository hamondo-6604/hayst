<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_number',
        'bus_name',
        'bus_type_id',
        'seat_layout_id',
        'total_seats',
        'default_seat_type',
        'bus_img',
        'status',
        'description',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function type(): BelongsTo
    {
        return $this->belongsTo(BusType::class, 'bus_type_id');
    }

    public function seatLayout(): BelongsTo
    {
        return $this->belongsTo(SeatLayout::class, 'seat_layout_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Amenities this bus offers, with optional per-seat-type and note via pivot.
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'bus_amenities')
                    ->withPivot(['seat_type_id', 'note']);
    }

    // ------------------------------------------------------------------
    // ACCESSORS
    // ------------------------------------------------------------------

    public function availableSeatsForTrip(int $tripId): int
    {
        $bookedSeatIds = Booking::where('trip_id', $tripId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('seat_id');

        return $this->seats()
            ->whereNotIn('id', $bookedSeatIds)
            ->where('status', 'available')
            ->count();
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}