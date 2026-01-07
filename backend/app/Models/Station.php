<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'city_id',
        'city', // Keep for backward compatibility
        'address',
        'address_ar',
        'latitude',
        'longitude',
        'operating_hours',
        'contact_phone',
        'image',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Get the city this station belongs to.
     */
    public function cityRelation()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Get the wheelchairs at this station.
     */
    public function wheelchairs()
    {
        return $this->hasMany(Wheelchair::class);
    }

    /**
     * Get the bookings at this station.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get available wheelchairs count.
     */
    public function getAvailableWheelchairsCountAttribute(): int
    {
        return $this->wheelchairs()->where('status', 'available')->count();
    }

    /**
     * Scope active stations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the city name (from relation or legacy field).
     */
    public function getCityNameAttribute(): string
    {
        return $this->cityRelation?->name ?? $this->city ?? '';
    }
}

