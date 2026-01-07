<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Wheelchair;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = auth()->user()->bookings()
            ->with(['wheelchair.wheelchairType', 'station', 'payments'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10);

        return view('web.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Wheelchair $wheelchair)
    {
        $wheelchair->load(['wheelchairType', 'station']);

        // Get stations for pickup selection
        $stations = Station::active()->get();

        return view('web.bookings.create', compact('wheelchair', 'stations'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'wheelchair_id' => 'required|exists:wheelchairs,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_type' => 'required|in:self,delivery',
            'delivery_address' => 'required_if:pickup_type,delivery',
            'station_id' => 'required_if:pickup_type,self|exists:stations,id',
            'addons' => 'nullable|array',
            'promo_code' => 'nullable|string',
            'notes' => 'nullable|string|max:500',
        ]);

        $wheelchair = Wheelchair::with('wheelchairType')->findOrFail($validated['wheelchair_id']);

        // Check availability
        if (!$wheelchair->isAvailableForDates($validated['start_date'], $validated['end_date'])) {
            return back()->withErrors(['wheelchair' => 'This wheelchair is not available for the selected dates.']);
        }

        // Calculate pricing
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate);

        $rentalAmount = $wheelchair->wheelchairType->calculateRate($days);
        $deliveryFee = $validated['pickup_type'] === 'delivery' ? 30 : 0;
        $depositAmount = $wheelchair->wheelchairType->deposit_amount;

        // Calculate discount (simple example)
        $discountAmount = 0;
        if ($validated['promo_code'] === 'HAJJ2026') {
            $discountAmount = $rentalAmount * 0.20; // 20% discount
        }

        $subtotal = $rentalAmount - $discountAmount + $deliveryFee;
        $vatAmount = $subtotal * 0.15; // 15% VAT
        $totalAmount = $subtotal + $vatAmount + $depositAmount;

        // Create booking
        $booking = Booking::create([
            'booking_code' => 'MK-' . date('Y') . '-' . strtoupper(Str::random(4)),
            'user_id' => auth()->id(),
            'wheelchair_id' => $wheelchair->id,
            'station_id' => $validated['station_id'] ?? $wheelchair->station_id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'pickup_type' => $validated['pickup_type'],
            'delivery_address' => $validated['delivery_address'] ?? null,
            'status' => 'pending',
            'rental_amount' => $rentalAmount,
            'delivery_fee' => $deliveryFee,
            'discount_amount' => $discountAmount,
            'vat_amount' => $vatAmount,
            'deposit_amount' => $depositAmount,
            'total_amount' => $totalAmount,
            'promo_code' => $validated['promo_code'] ?? null,
            'addons' => $validated['addons'] ?? [],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('payment.checkout', $booking)
            ->with('success', 'Booking created! Please complete payment.');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['wheelchair.wheelchairType', 'station', 'payments']);

        return view('web.bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$booking->canBeCancelled()) {
            return back()->withErrors(['booking' => 'This booking cannot be cancelled.']);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Cancelled by user',
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Mark booking as picked up.
     */
    public function pickup(Booking $booking)
    {
        // Validate ownership
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Validate status - only confirmed bookings can be picked up
        if ($booking->status !== 'confirmed') {
            return back()->withErrors(['booking' => 'This booking cannot be picked up.']);
        }

        // Update booking status and timestamp
        $booking->update([
            'status' => 'active',
            'picked_up_at' => now(),
        ]);

        // Update wheelchair status to rented
        $booking->wheelchair->update([
            'status' => 'rented',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Wheelchair picked up successfully! Enjoy your rental.');
    }

    /**
     * Mark booking as returned.
     */
    public function returnWheelchair(Booking $booking)
    {
        // Validate ownership
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Validate status - only active bookings can be returned
        if ($booking->status !== 'active') {
            return back()->withErrors(['booking' => 'This booking cannot be returned.']);
        }

        // Update booking status and timestamp
        $booking->update([
            'status' => 'completed',
            'returned_at' => now(),
        ]);

        // Update wheelchair status to available
        $booking->wheelchair->update([
            'status' => 'available',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Wheelchair returned successfully! Thank you for using MobilityKSA.');
    }
}
