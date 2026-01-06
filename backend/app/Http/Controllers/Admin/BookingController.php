<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'wheelchair.wheelchairType', 'station']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by booking code or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->latest()->paginate(15);
        
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        // Mark as read by admin
        if (!$booking->admin_read_at) {
            $booking->update(['admin_read_at' => now()]);
        }
        
        $booking->load(['user', 'wheelchair.wheelchairType', 'station', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $booking->load(['user', 'wheelchair.wheelchairType', 'station']);
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled',
            'notes' => 'nullable|string',
            'cancellation_reason' => 'nullable|string|required_if:status,cancelled',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
