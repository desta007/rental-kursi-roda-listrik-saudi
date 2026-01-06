<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wheelchair extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'wheelchair_type_id',
        'station_id',
        'brand',
        'model',
        'battery_capacity',
        'status',
        'last_maintenance',
        'next_maintenance',
        'photos',
        'notes',
    ];

    protected $casts = [
        'photos' => 'array',
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
    ];

    /**
     * Get the wheelchair type.
     */
    public function wheelchairType()
    {
        return $this->belongsTo(WheelchairType::class);
    }

    /**
     * Get the station.
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Get the bookings for this wheelchair.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if wheelchair is available for given dates.
     */
    public function isAvailableForDates($startDate, $endDate): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        return !$this->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }

    /**
     * Scope available wheelchairs.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope by station.
     */
    public function scopeAtStation($query, $stationId)
    {
        return $query->where('station_id', $stationId);
    }
}
