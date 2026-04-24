<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayoutMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_layout_id',
        'seat_type_id',
        'row_number',
        'column_number',
        'seat_label',
        'cell_type',
        'is_bookable',
    ];

    protected $casts = [
        'is_bookable' => 'boolean',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function seatLayout(): BelongsTo
    {
        return $this->belongsTo(SeatLayout::class);
    }

    public function seatType(): BelongsTo
    {
        return $this->belongsTo(SeatType::class);
    }

    // ------------------------------------------------------------------
    // ACCESSORS
    // ------------------------------------------------------------------

    public function isActualSeat(): bool
    {
        return $this->cell_type === 'seat' && $this->is_bookable;
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeBookable($query)
    {
        return $query->where('cell_type', 'seat')->where('is_bookable', true);
    }

    public function scopeForLayout($query, int $layoutId)
    {
        return $query->where('seat_layout_id', $layoutId)
                     ->orderBy('row_number')
                     ->orderBy('column_number');
    }
}