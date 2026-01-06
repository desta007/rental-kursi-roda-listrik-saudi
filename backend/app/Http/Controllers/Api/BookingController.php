<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Wheelchair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * List user's bookings.
     */
    public function index(Request $request)
    {
        $query = $request->user()
            ->bookings()
            ->with(['wheelchair.wheelchairType', 'station', 'payments']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * Create new booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'wheelchair_id' => 'required|uuid|exists:wheelchairs,id',
            'station_id' => 'required|uuid|exists:stations,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_type' => 'required|in:self_pickup,delivery',
            'delivery_address' => 'required_if:pickup_type,delivery',
            'delivery_latitude' => 'nullable|numeric',
            'delivery_longitude' => 'nullable|numeric',
            'addons' => 'nullable|array',
            'promo_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $wheelchair = Wheelchair::with('wheelchairType')->findOrFail($request->wheelchair_id);

        // Check availability
        if (!$wheelchair->isAvailableForDates($request->start_date, $request->end_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Wheelchair is not available for the selected dates',
            ], 400);
        }

        // Calculate pricing
        $days = now()->parse($request->start_date)->diffInDays(now()->parse($request->end_date));
        $rentalAmount = $wheelchair->wheelchairType->calculateRate($days);
        $deliveryFee = $request->pickup_type === 'delivery' ? 50 : 0;
        $discountAmount = 0; // TODO: Apply promo code discount
        $subtotal = $rentalAmount + $deliveryFee - $discountAmount;
        $vatAmount = $subtotal * 0.15;
        $depositAmount = $wheelchair->wheelchairType->deposit_amount;
        $totalAmount = $subtotal + $vatAmount + $depositAmount;

        DB::beginTransaction();

        try {
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'wheelchair_id' => $request->wheelchair_id,
                'station_id' => $request->station_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'pickup_type' => $request->pickup_type,
                'delivery_address' => $request->delivery_address,
                'delivery_latitude' => $request->delivery_latitude,
                'delivery_longitude' => $request->delivery_longitude,
                'rental_amount' => $rentalAmount,
                'delivery_fee' => $deliveryFee,
                'discount_amount' => $discountAmount,
                'vat_amount' => $vatAmount,
                'deposit_amount' => $depositAmount,
                'total_amount' => $totalAmount,
                'promo_code' => $request->promo_code,
                'addons' => $request->addons,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking->load(['wheelchair.wheelchairType', 'station']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking',
            ], 500);
        }
    }

    /**
     * Get booking detail.
     */
    public function show(Request $request, $id)
    {
        $booking = $request->user()
            ->bookings()
            ->with(['wheelchair.wheelchairType', 'station', 'payments'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $booking,
        ]);
    }

    /**
     * Cancel booking.
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $booking = $request->user()
            ->bookings()
            ->findOrFail($id);

        if (!$booking->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'This booking cannot be cancelled',
            ], 400);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
        ]);

        // TODO: Process refund if payment was made

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'data' => $booking,
        ]);
    }
}
