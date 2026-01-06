<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::withCount(['wheelchairs', 'bookings'])->latest()->paginate(15);
        return view('admin.stations.index', compact('stations'));
    }

    public function create()
    {
        return view('admin.stations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'address_ar' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'operating_hours' => 'required|string',
            'contact_phone' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        Station::create($validated);

        return redirect()->route('admin.stations.index')
            ->with('success', 'Station created successfully.');
    }

    public function edit(Station $station)
    {
        return view('admin.stations.edit', compact('station'));
    }

    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string',
            'address_ar' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'operating_hours' => 'required|string',
            'contact_phone' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $station->update($validated);

        return redirect()->route('admin.stations.index')
            ->with('success', 'Station updated successfully.');
    }

    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->route('admin.stations.index')
            ->with('success', 'Station deleted successfully.');
    }
}
