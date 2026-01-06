<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WheelchairType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'image',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'deposit_amount',
        'battery_range_km',
        'max_weight_kg',
        'max_speed_kmh',
        'features',
        'specifications',
        'is_active',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'weekly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'features' => 'array',
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the wheelchairs of this type.
     */
    public function wheelchairs()
    {
        return $this->hasMany(Wheelchair::class);
    }

    /**
     * Calculate rental rate based on duration.
     */
    public function calculateRate(int $days): float
    {
        if ($days >= 30) {
            return $this->monthly_rate * ceil($days / 30);
        } elseif ($days >= 7) {
            return $this->weekly_rate * ceil($days / 7);
        } else {
            return $this->daily_rate * $days;
        }
    }

    /**
     * Scope active types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
