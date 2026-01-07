<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'user_id',
        'wheelchair_id',
        'station_id',
        'start_date',
        'end_date',
        'pickup_type',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'status',
        'rental_amount',
        'delivery_fee',
        'discount_amount',
        'vat_amount',
        'deposit_amount',
        'total_amount',
        'promo_code',
        'addons',
        'notes',
        'cancellation_reason',
        'picked_up_at',
        'returned_at',
        'admin_read_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'picked_up_at' => 'datetime',
        'returned_at' => 'datetime',
        'admin_read_at' => 'datetime',
        'rental_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'addons' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_code) {
                $booking->booking_code = 'MK-' . date('Y') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wheelchair.
     */
    public function wheelchair()
    {
        return $this->belongsTo(Wheelchair::class);
    }

    /**
     * Get the station.
     */
    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Get the payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get number of rental days.
     */
    public function getDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Check if booking is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Check if booking can be picked up.
     */
    public function canBePickedUp(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking can be returned.
     */
    public function canBeReturned(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Scope by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope active bookings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
