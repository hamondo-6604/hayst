<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_expiry',
        'license_photo',
        'experience_years',
        'contact_number',
        'address',
        'status',
    ];

    protected $casts = [
        'license_expiry' => 'date',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    // ------------------------------------------------------------------
    // ACCESSORS
    // ------------------------------------------------------------------

    /**
     * Convenience: get the driver's name directly.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown';
    }

    public function isLicenseExpired(): bool
    {
        return $this->license_expiry->isPast();
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}