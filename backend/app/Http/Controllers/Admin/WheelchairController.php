<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\Wheelchair;
use App\Models\WheelchairType;
use Illuminate\Http\Request;

class WheelchairController extends Controller
{
    public function index()
    {
        $wheelchairs = Wheelchair::with(['wheelchairType', 'station'])->withCount('bookings')->latest()->paginate(15);
        return view('admin.wheelchairs.index', compact('wheelchairs'));
    }

    public function create()
    {
        $wheelchairTypes = WheelchairType::where('is_active', true)->get();
        $stations = Station::where('is_active', true)->get();
        return view('admin.wheelchairs.create', compact('wheelchairTypes', 'stations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:wheelchairs',
            'wheelchair_type_id' => 'required|exists:wheelchair_types,id',
            'station_id' => 'required|exists:stations,id',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'battery_capacity' => 'required|integer|min:0|max:100',
            'status' => 'required|in:available,rented,maintenance,retired',
            'notes' => 'nullable|string',
        ]);

        Wheelchair::create($validated);

        return redirect()->route('admin.wheelchairs.index')
            ->with('success', 'Wheelchair created successfully.');
    }

    public function edit(Wheelchair $wheelchair)
    {
        $wheelchairTypes = WheelchairType::where('is_active', true)->get();
        $stations = Station::where('is_active', true)->get();
        return view('admin.wheelchairs.edit', compact('wheelchair', 'wheelchairTypes', 'stations'));
    }

    public function update(Request $request, Wheelchair $wheelchair)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:wheelchairs,code,' . $wheelchair->id,
            'wheelchair_type_id' => 'required|exists:wheelchair_types,id',
            'station_id' => 'required|exists:stations,id',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'battery_capacity' => 'required|integer|min:0|max:100',
            'status' => 'required|in:available,rented,maintenance,retired',
            'notes' => 'nullable|string',
        ]);

        $wheelchair->update($validated);

        return redirect()->route('admin.wheelchairs.index')
            ->with('success', 'Wheelchair updated successfully.');
    }

    public function destroy(Wheelchair $wheelchair)
    {
        $wheelchair->delete();
        return redirect()->route('admin.wheelchairs.index')
            ->with('success', 'Wheelchair deleted successfully.');
    }
}
