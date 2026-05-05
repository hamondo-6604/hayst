<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'route_id',
        'schedule_id',              // NEW
        'bus_id',
        'driver_id',
        'departure_terminal_id',    // NEW
        'arrival_terminal_id',      // NEW
        'trip_code',
        'trip_date',
        'departure_time',
        'arrival_time',
        'available_seats',
        'fare',
        'status',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'trip_date'      => 'date',
        'departure_time' => 'datetime',
        'arrival_time'   => 'datetime',
        'fare'           => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function route(): BelongsTo
    {
        return $this->belongsTo(BusRoute::class, 'route_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function departureTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'departure_terminal_id');
    }

    public function arrivalTerminal(): BelongsTo
    {
        return $this->belongsTo(Terminal::class, 'arrival_terminal_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    // ------------------------------------------------------------------
    // ACCESSORS
    // ------------------------------------------------------------------

    public function getFormattedDepartureAttribute(): string
    {
        return $this->departure_time?->format('D, M j · h:i A') ?? '—';
    }

    /**
     * e.g. "Cubao Terminal, 6:00 AM"
     */
    public function getDepartureSummaryAttribute(): string
    {
        $terminal = $this->departureTerminal?->name
            ?? $this->route?->originCity?->name
            ?? '—';

        return $terminal . ', ' . ($this->departure_time?->format('g:i A') ?? '—');
    }

    public function hasAvailableSeats(): bool
    {
        return $this->available_seats > 0;
    }

    public function getAvailableSeatsAttribute($value): int
    {
        if ($this->relationLoaded('bookings')) {
            $booked = 0;
            foreach ($this->bookings as $booking) {
                if (in_array($booking->status, ['pending', 'confirmed'])) {
                    if ($booking->relationLoaded('bookingSeats')) {
                        $booked += $booking->bookingSeats->whereIn('status', ['reserved', 'confirmed'])->count();
                    } else {
                        $booked += $booking->bookingSeats()->whereIn('status', ['reserved', 'confirmed'])->count();
                    }
                }
            }
            $total = $this->bus ? $this->bus->total_seats : (int) $value;
            return max(0, $total - $booked);
        }

        $booked = \App\Models\BookingSeat::whereHas('booking', function ($q) {
            $q->where('trip_id', $this->id)
              ->whereIn('status', ['pending', 'confirmed']);
        })->whereIn('status', ['reserved', 'confirmed'])->count();
        
        $total = $this->bus ? $this->bus->total_seats : (int) $value;
        return max(0, $total - $booked);
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('trip_date', '>=', now()->toDateString())
                     ->where('is_active', true);
    }

    public function scopeForRoute($query, int $routeId)
    {
        return $query->where('route_id', $routeId);
    }
}