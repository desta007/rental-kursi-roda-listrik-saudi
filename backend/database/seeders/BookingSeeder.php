<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wheelchair;
use App\Models\Station;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    /**
     * Create sample bookings with various statuses.
     */
    public function run(): void
    {
        $users = User::where('verification_status', 'verified')->get();
        $wheelchairs = Wheelchair::with('wheelchairType')->where('status', 'available')->get();

        if ($users->isEmpty() || $wheelchairs->isEmpty()) {
            return;
        }

        $bookings = [
            // Active bookings (ongoing)
            [
                'status' => 'active',
                'days_offset' => -2, // Started 2 days ago
                'duration' => 7,
            ],
            [
                'status' => 'active',
                'days_offset' => -1, // Started yesterday
                'duration' => 5,
            ],
            [
                'status' => 'active',
                'days_offset' => 0, // Started today
                'duration' => 3,
            ],
            // Confirmed bookings (upcoming)
            [
                'status' => 'confirmed',
                'days_offset' => 2, // Starts in 2 days
                'duration' => 7,
            ],
            [
                'status' => 'confirmed',
                'days_offset' => 5, // Starts in 5 days
                'duration' => 10,
            ],
            // Pending bookings
            [
                'status' => 'pending',
                'days_offset' => 3,
                'duration' => 5,
            ],
            // Completed bookings (past)
            [
                'status' => 'completed',
                'days_offset' => -14, // Started 2 weeks ago
                'duration' => 7,
            ],
            [
                'status' => 'completed',
                'days_offset' => -10,
                'duration' => 5,
            ],
            [
                'status' => 'completed',
                'days_offset' => -30,
                'duration' => 14,
            ],
            // Cancelled booking
            [
                'status' => 'cancelled',
                'days_offset' => -5,
                'duration' => 7,
                'cancellation_reason' => 'Plans changed due to health reasons',
            ],
        ];

        foreach ($bookings as $index => $bookingData) {
            $user = $users->random();
            $wheelchair = $wheelchairs->random();
            $station = $wheelchair->station;
            $type = $wheelchair->wheelchairType;

            $startDate = now()->addDays($bookingData['days_offset']);
            $endDate = $startDate->copy()->addDays($bookingData['duration']);

            // Calculate pricing
            $rentalAmount = $type->calculateRate($bookingData['duration']);
            $deliveryFee = rand(0, 1) ? 50 : 0;
            $vatAmount = ($rentalAmount + $deliveryFee) * 0.15;
            $depositAmount = $type->deposit_amount;
            $totalAmount = $rentalAmount + $deliveryFee + $vatAmount + $depositAmount;

            $booking = Booking::create([
                'booking_code' => 'MK-2026-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'wheelchair_id' => $wheelchair->id,
                'station_id' => $station->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pickup_type' => $deliveryFee > 0 ? 'delivery' : 'self_pickup',
                'delivery_address' => $deliveryFee > 0 ? 'Hilton Makkah Convention Hotel, Room 1205' : null,
                'status' => $bookingData['status'],
                'rental_amount' => $rentalAmount,
                'delivery_fee' => $deliveryFee,
                'vat_amount' => $vatAmount,
                'deposit_amount' => $depositAmount,
                'total_amount' => $totalAmount,
                'cancellation_reason' => $bookingData['cancellation_reason'] ?? null,
                'picked_up_at' => in_array($bookingData['status'], ['active', 'completed']) ? $startDate : null,
                'returned_at' => $bookingData['status'] === 'completed' ? $endDate : null,
            ]);

            // Create payment for non-pending bookings
            if ($bookingData['status'] !== 'pending') {
                $paymentStatus = match($bookingData['status']) {
                    'cancelled' => 'refunded',
                    default => 'completed',
                };

                Payment::create([
                    'booking_id' => $booking->id,
                    'type' => 'rental',
                    'payment_method' => 'stripe',
                    'amount' => $totalAmount,
                    'currency' => 'SAR',
                    'status' => $paymentStatus,
                    'stripe_payment_intent_id' => 'pi_' . Str::random(24),
                    'stripe_charge_id' => 'ch_' . Str::random(24),
                    'paid_at' => $startDate->copy()->subHours(rand(1, 24)),
                ]);
            }

            // Update wheelchair status for active bookings
            if ($bookingData['status'] === 'active') {
                $wheelchair->update(['status' => 'rented']);
            }
        }
    }
}
