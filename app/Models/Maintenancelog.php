<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'logged_by',
        'title',
        'description',
        'type',
        'status',
        'maintenance_date',
        'completed_date',
        'cost',
        'performed_by',
        'parts_replaced',
        'next_maintenance_due',
    ];

    protected $casts = [
        'maintenance_date'     => 'date',
        'completed_date'       => 'date',
        'next_maintenance_due' => 'date',
        'cost'                 => 'decimal:2',
    ];

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    public function getFormattedCostAttribute(): string
    {
        return '₱' . number_format($this->cost ?? 0, 2);
    }

    public function isOverdue(): bool
    {
        return $this->next_maintenance_due?->isPast() ?? false;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['scheduled', 'in_progress']);
    }

    public function scopeForBus($query, int $busId)
    {
        return $query->where('bus_id', $busId);
    }
}

