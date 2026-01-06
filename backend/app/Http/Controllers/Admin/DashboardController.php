<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Station;
use App\Models\User;
use App\Models\Wheelchair;
use App\Models\WheelchairType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'active_rentals' => Booking::where('status', 'active')->count(),
            'total_customers' => User::count(),
            'total_wheelchairs' => Wheelchair::count(),
            'revenue_mtd' => Booking::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
        ];

        $recentBookings = Booking::with(['user', 'wheelchair.wheelchairType'])
            ->latest()
            ->take(5)
            ->get();

        $wheelchairAvailability = WheelchairType::withCount([
            'wheelchairs',
            'wheelchairs as available_count' => function($query) {
                $query->where('status', 'available');
            }
        ])->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'wheelchairAvailability'));
    }

    /**
     * Global search for admin.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Search bookings
        $bookings = Booking::with('user')
            ->where('booking_code', 'like', "%{$query}%")
            ->orWhereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->take(5)
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'user_name' => $booking->user->name ?? 'Unknown',
                    'status' => ucfirst($booking->status),
                ];
            });

        // Search users
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ];
            });

        // Search wheelchairs
        $wheelchairs = Wheelchair::with('wheelchairType')
            ->where('code', 'like', "%{$query}%")
            ->orWhere('brand', 'like', "%{$query}%")
            ->orWhere('model', 'like', "%{$query}%")
            ->take(5)
            ->get()
            ->map(function($wheelchair) {
                return [
                    'id' => $wheelchair->id,
                    'code' => $wheelchair->code,
                    'type_name' => $wheelchair->wheelchairType->name ?? 'N/A',
                    'status' => ucfirst($wheelchair->status),
                ];
            });

        return response()->json([
            'bookings' => $bookings,
            'users' => $users,
            'wheelchairs' => $wheelchairs,
        ]);
    }
}

