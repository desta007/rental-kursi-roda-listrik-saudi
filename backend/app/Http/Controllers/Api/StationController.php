<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * List all active stations.
     */
    public function index(Request $request)
    {
        $query = Station::active()
            ->withCount(['wheelchairs as available_wheelchairs' => function ($q) {
                $q->where('status', 'available');
            }]);

        // Filter by city
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        // Order by nearest location if coordinates provided
        if ($request->has('lat') && $request->has('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            
            $query->selectRaw("*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$lat, $lng, $lat]
            )
            ->orderBy('distance');
        }

        $stations = $query->get();

        return response()->json([
            'success' => true,
            'data' => $stations,
        ]);
    }

    /**
     * Get station detail.
     */
    public function show($id)
    {
        $station = Station::with(['wheelchairs' => function ($q) {
                $q->with('wheelchairType')->where('status', 'available');
            }])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $station,
        ]);
    }
}
