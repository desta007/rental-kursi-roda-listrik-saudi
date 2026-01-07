<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Wheelchair;
use App\Models\WheelchairType;
use App\Models\Station;
use Illuminate\Http\Request;

class WheelchairController extends Controller
{
    /**
     * Display a listing of wheelchairs.
     */
    public function index(Request $request)
    {
        $query = Wheelchair::with(['wheelchairType', 'station'])->available();

        // Get selected location (station) from session for filtering
        $selectedLocationId = session('selected_location_id');
        $selectedLocation = null;
        if ($selectedLocationId) {
            $selectedLocation = Station::find($selectedLocationId);
        }

        // Filter by selected station from session if no specific station filter applied
        if ($selectedLocation && !$request->filled('station')) {
            $query->where('station_id', $selectedLocation->id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('wheelchair_type_id', $request->type);
        }

        // Filter by station (overrides location filter)
        if ($request->filled('station')) {
            $query->where('station_id', $request->station);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('wheelchairType', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'price_low');
        switch ($sortBy) {
            case 'price_high':
                $query->join('wheelchair_types', 'wheelchairs.wheelchair_type_id', '=', 'wheelchair_types.id')
                    ->orderBy('wheelchair_types.daily_rate', 'desc')
                    ->select('wheelchairs.*');
                break;
            case 'price_low':
            default:
                $query->join('wheelchair_types', 'wheelchairs.wheelchair_type_id', '=', 'wheelchair_types.id')
                    ->orderBy('wheelchair_types.daily_rate', 'asc')
                    ->select('wheelchairs.*');
                break;
        }

        $wheelchairs = $query->paginate(10);

        // Get filter options (filter stations by city if location is selected)
        $types = WheelchairType::active()->get();

        // Show all stations but pass selectedLocation for display
        $stations = Station::active()->get();

        return view('web.wheelchairs.index', compact('wheelchairs', 'types', 'stations', 'selectedLocation'));
    }

    /**
     * Display the specified wheelchair.
     */
    public function show($id)
    {
        $wheelchair = Wheelchair::with(['wheelchairType', 'station'])
            ->findOrFail($id);

        // Get similar wheelchairs
        $similarWheelchairs = Wheelchair::with(['wheelchairType', 'station'])
            ->where('wheelchair_type_id', $wheelchair->wheelchair_type_id)
            ->where('id', '!=', $wheelchair->id)
            ->available()
            ->take(3)
            ->get();

        return view('web.wheelchairs.show', compact('wheelchair', 'similarWheelchairs'));
    }
}
