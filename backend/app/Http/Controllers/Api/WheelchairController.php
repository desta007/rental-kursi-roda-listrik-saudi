<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wheelchair;
use App\Models\WheelchairType;
use Illuminate\Http\Request;

class WheelchairController extends Controller
{
    /**
     * List all wheelchair types.
     */
    public function types(Request $request)
    {
        $types = WheelchairType::active()
            ->orderBy('daily_rate')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }

    /**
     * List available wheelchairs.
     */
    public function index(Request $request)
    {
        $query = Wheelchair::with(['wheelchairType', 'station'])
            ->available();

        // Filter by type
        if ($request->has('type_id')) {
            $query->where('wheelchair_type_id', $request->type_id);
        }

        // Filter by station
        if ($request->has('station_id')) {
            $query->where('station_id', $request->station_id);
        }

        // Filter by date range availability
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['pending', 'confirmed', 'active'])
                  ->where(function ($sub) use ($startDate, $endDate) {
                      $sub->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($deep) use ($startDate, $endDate) {
                              $deep->where('start_date', '<=', $startDate)
                                   ->where('end_date', '>=', $endDate);
                          });
                  });
            });
        }

        $wheelchairs = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $wheelchairs,
        ]);
    }

    /**
     * Get wheelchair detail.
     */
    public function show($id)
    {
        $wheelchair = Wheelchair::with(['wheelchairType', 'station'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $wheelchair,
        ]);
    }

    /**
     * Check wheelchair availability.
     */
    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $wheelchair = Wheelchair::findOrFail($id);
        
        $isAvailable = $wheelchair->isAvailableForDates(
            $request->start_date,
            $request->end_date
        );

        $days = now()->parse($request->start_date)->diffInDays(now()->parse($request->end_date));
        $rentalAmount = $wheelchair->wheelchairType->calculateRate($days);
        $vatAmount = $rentalAmount * 0.15; // 15% Saudi VAT
        $depositAmount = $wheelchair->wheelchairType->deposit_amount;

        return response()->json([
            'success' => true,
            'data' => [
                'is_available' => $isAvailable,
                'days' => $days,
                'pricing' => [
                    'rental_amount' => $rentalAmount,
                    'vat_amount' => $vatAmount,
                    'deposit_amount' => $depositAmount,
                    'total' => $rentalAmount + $vatAmount + $depositAmount,
                ],
            ],
        ]);
    }
}
