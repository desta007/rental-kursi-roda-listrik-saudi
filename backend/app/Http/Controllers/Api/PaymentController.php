<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Initiate payment for a booking.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|uuid|exists:bookings,id',
        ]);

        $booking = $request->user()
            ->bookings()
            ->where('status', 'pending')
            ->findOrFail($request->booking_id);

        // Check if payment already exists
        $existingPayment = $booking->payments()
            ->where('status', 'pending')
            ->first();

        if ($existingPayment && $existingPayment->stripe_payment_intent_id) {
            // Return existing payment intent
            $paymentIntent = PaymentIntent::retrieve($existingPayment->stripe_payment_intent_id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'payment_id' => $existingPayment->id,
                    'client_secret' => $paymentIntent->client_secret,
                    'amount' => $booking->total_amount,
                    'currency' => 'sar',
                ],
            ]);
        }

        // Create Stripe PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => (int)($booking->total_amount * 100), // Convert to halalas
            'currency' => 'sar',
            'metadata' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'user_id' => $request->user()->id,
            ],
        ]);

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'type' => 'rental',
            'payment_method' => 'stripe',
            'amount' => $booking->total_amount,
            'currency' => 'SAR',
            'status' => 'pending',
            'stripe_payment_intent_id' => $paymentIntent->id,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'payment_id' => $payment->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $booking->total_amount,
                'currency' => 'sar',
            ],
        ]);
    }

    /**
     * Confirm payment after successful Stripe payment.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|uuid|exists:payments,id',
            'payment_intent_id' => 'required|string',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Verify with Stripe
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($paymentIntent->status !== 'succeeded') {
            return response()->json([
                'success' => false,
                'message' => 'Payment not completed',
            ], 400);
        }

        // Update payment
        $payment->markAsCompleted(
            $paymentIntent->latest_charge,
            $paymentIntent->charges->data[0]->receipt_url ?? null
        );

        // Update booking status
        $payment->booking->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed successfully',
            'data' => [
                'payment' => $payment,
                'booking' => $payment->booking->load(['wheelchair.wheelchairType', 'station']),
            ],
        ]);
    }

    /**
     * Get payment status.
     */
    public function status($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }

    /**
     * Stripe webhook handler.
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentSuccess($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailure($paymentIntent);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentSuccess($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($payment && $payment->status !== 'completed') {
            $payment->markAsCompleted(
                $paymentIntent->latest_charge,
                $paymentIntent->charges->data[0]->receipt_url ?? null
            );
            $payment->booking->update(['status' => 'confirmed']);
        }
    }

    private function handlePaymentFailure($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($payment) {
            $payment->update(['status' => 'failed']);
        }
    }
}
