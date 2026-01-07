<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display payment checkout page.
     */
    public function checkout(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'This booking has already been processed.');
        }

        $booking->load(['wheelchair.wheelchairType', 'station']);

        return view('web.payment', compact('booking'));
    }

    /**
     * Process payment.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:card,apple_pay,mada',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'type' => 'rental',
            'payment_method' => $validated['payment_method'],
            'amount' => $booking->total_amount,
            'currency' => 'SAR',
            'status' => 'completed', // Simulated - in production this goes through Stripe
            'paid_at' => now(),
        ]);

        // Update booking status
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Payment successful! Your booking is confirmed.');
    }
}
