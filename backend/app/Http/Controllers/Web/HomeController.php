<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\Wheelchair;
use App\Models\WheelchairType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(Request $request)
    {
        // Get all active stations for location selector
        $allStations = Station::active()->get();

        // Get selected location from session or use first station
        $selectedLocationId = session('selected_location_id');
        $selectedLocation = null;

        if ($selectedLocationId) {
            $selectedLocation = Station::find($selectedLocationId);
        }

        // If no location selected, use first station as default
        if (!$selectedLocation && $allStations->count() > 0) {
            $selectedLocation = $allStations->first();
            session(['selected_location_id' => $selectedLocation->id]);
        }

        // Get featured wheelchair types
        $wheelchairTypes = WheelchairType::active()
            ->orderBy('daily_rate')
            ->take(4)
            ->get();

        // Get nearby stations with available wheelchair count (show all stations but highlight selected)
        $stations = Station::active()
            ->withCount([
                'wheelchairs' => function ($query) {
                    $query->where('status', 'available');
                }
            ])
            ->take(5)
            ->get();

        // Get featured wheelchairs (available ones, filter by specific station if selected)
        $wheelchairsQuery = Wheelchair::with(['wheelchairType', 'station'])
            ->available();

        if ($selectedLocation) {
            $wheelchairsQuery->where('station_id', $selectedLocation->id);
        }

        $featuredWheelchairs = $wheelchairsQuery->take(4)->get();

        // Get user's active booking if logged in
        $activeBooking = null;
        if (auth()->check()) {
            $activeBooking = auth()->user()->bookings()
                ->with(['wheelchair.wheelchairType'])
                ->whereIn('status', ['confirmed', 'active'])
                ->latest()
                ->first();
        }

        return view('web.home', compact(
            'wheelchairTypes',
            'stations',
            'featuredWheelchairs',
            'activeBooking',
            'allStations',
            'selectedLocation'
        ));
    }

    /**
     * Update selected location (AJAX).
     */
    public function setLocation(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:stations,id'
        ]);

        session(['selected_location_id' => $request->location_id]);

        return response()->json(['success' => true]);
    }
}

