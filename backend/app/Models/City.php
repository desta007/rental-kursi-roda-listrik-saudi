<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'country',
        'country_code',
        'latitude',
        'longitude',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Get the stations in this city.
     */
    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    /**
     * Get active stations in this city.
     */
    public function activeStations()
    {
        return $this->hasMany(Station::class)->where('is_active', true);
    }

    /**
     * Scope active cities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get total available wheelchairs count in this city.
     */
    public function getAvailableWheelchairsCountAttribute(): int
    {
        return $this->stations()
            ->with('wheelchairs')
            ->get()
            ->sum(function ($station) {
                return $station->wheelchairs->where('status', 'available')->count();
            });
    }
}
