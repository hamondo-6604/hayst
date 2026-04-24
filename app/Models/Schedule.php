<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'bus_id',
        'driver_id',
        'schedule_code',
        'days_of_week',
        'departure_time',
        'arrival_time',
        'fare',
        'valid_from',
        'valid_until',
        'status',
        'notes',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'valid_from'   => 'date',
        'valid_until'  => 'date',
        'fare'         => 'decimal:2',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function route(): BelongsTo
    {
        return $this->belongsTo(BusRoute::class, 'route_id');
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /** All trips that were generated from this schedule. */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    // ------------------------------------------------------------------
    // BUSINESS LOGIC
    // ------------------------------------------------------------------

    /**
     * Check whether this schedule runs on a given day name.
     * e.g. runsOn('monday'), runsOn('daily')
     */
    public function runsOn(string $day): bool
    {
        $days = $this->days_of_week ?? [];

        if (in_array('daily', $days)) {
            return true;
        }

        return in_array(strtolower($day), $days);
    }

    /**
     * Check whether this schedule is valid on a given date.
     */
    public function isValidOn(Carbon $date): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($date->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $date->gt($this->valid_until)) {
            return false;
        }

        return $this->runsOn(strtolower($date->englishDayOfWeek));
    }

    /**
     * Get the next N dates this schedule runs.
     */
    public function nextDates(int $count = 7): array
    {
        $dates = [];
        $date  = now()->startOfDay();
        $limit = 90; // don't search more than 90 days ahead

        while (count($dates) < $count && $limit-- > 0) {
            if ($this->isValidOn($date)) {
                $dates[] = $date->copy();
            }
            $date->addDay();
        }

        return $dates;
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeValidOn($query, Carbon $date)
    {
        $dayName = strtolower($date->englishDayOfWeek);

        return $query->where('status', 'active')
            ->where('valid_from', '<=', $date->toDateString())
            ->where(fn ($q) =>
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', $date->toDateString())
            )
            ->where(fn ($q) =>
                $q->whereJsonContains('days_of_week', 'daily')
                  ->orWhereJsonContains('days_of_week', $dayName)
            );
    }
}