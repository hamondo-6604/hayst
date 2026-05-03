<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name',
        'seat_layout_id',
        'status',
        'description',
        'image_url',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function seatLayout(): BelongsTo
    {
        return $this->belongsTo(SeatLayout::class, 'seat_layout_id');
    }

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}