<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'type',
        'payment_method',
        'amount',
        'currency',
        'status',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'gateway_response',
        'receipt_url',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark as completed.
     */
    public function markAsCompleted($chargeId = null, $receiptUrl = null): void
    {
        $this->update([
            'status' => 'completed',
            'stripe_charge_id' => $chargeId,
            'receipt_url' => $receiptUrl,
            'paid_at' => now(),
        ]);
    }

    /**
     * Scope completed payments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
