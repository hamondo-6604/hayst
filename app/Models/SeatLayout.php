<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'layout_name',
        'total_rows',
        'total_columns',
        'capacity',
        'layout_map',       // JSON cache — source of truth is layout_maps table
        'status',
        'description',
    ];

    protected $casts = [
        'layout_map' => 'array',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function busTypes(): HasMany
    {
        return $this->hasMany(BusType::class, 'seat_layout_id');
    }

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class, 'seat_layout_id');
    }

    /**
     * Individual grid cells — the relational source of truth for layout structure.
     */
    public function layoutMaps(): HasMany
    {
        return $this->hasMany(LayoutMap::class)
                    ->orderBy('row_number')
                    ->orderBy('column_number');
    }

    /**
     * Only the bookable seat cells.
     */
    public function bookableSeats(): HasMany
    {
        return $this->hasMany(LayoutMap::class)
                    ->where('cell_type', 'seat')
                    ->where('is_bookable', true)
                    ->orderBy('row_number')
                    ->orderBy('column_number');
    }

    // ------------------------------------------------------------------
    // ACCESSORS & HELPERS
    // ------------------------------------------------------------------

    public function getEffectiveCapacityAttribute(): int
    {
        return $this->capacity ?? ($this->total_rows * $this->total_columns);
    }

    /**
     * Return the grid as a 2D array grouped by row_number,
     * reading from the relational layout_maps table.
     * Falls back to the JSON layout_map if no rows exist yet.
     */
    public function buildGrid(): array
    {
        $cells = $this->layoutMaps;

        if ($cells->isEmpty()) {
            return $this->layout_map ?? [];
        }

        return $cells->groupBy('row_number')
                     ->map(fn ($row) => $row->values())
                     ->toArray();
    }

    /**
     * Sync the JSON layout_map cache from the relational layout_maps rows.
     * Call this after bulk-inserting LayoutMap records.
     */
    public function rebuildJsonCache(): void
    {
        $this->layout_map = $this->buildGrid();
        $this->save();
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}