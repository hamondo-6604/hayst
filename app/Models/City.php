<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'province',
        'region',
        'status',
        'image_url',
    ];

    // ------------------------------------------------------------------
    // RELATIONSHIPS
    // ------------------------------------------------------------------

    /**
     * Routes departing from this city.
     */
    public function originRoutes(): HasMany
    {
        return $this->hasMany(BusRoute::class, 'origin_city_id');
    }

    /**
     * Routes arriving at this city.
     */
    public function destinationRoutes(): HasMany
    {
        return $this->hasMany(BusRoute::class, 'destination_city_id');
    }

    // ------------------------------------------------------------------
    // SCOPES
    // ------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}