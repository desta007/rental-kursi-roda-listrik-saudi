<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WheelchairType;
use Illuminate\Http\Request;

class WheelchairTypeController extends Controller
{
    public function index()
    {
        $wheelchairTypes = WheelchairType::withCount('wheelchairs')->latest()->paginate(15);
        return view('admin.wheelchair-types.index', compact('wheelchairTypes'));
    }

    public function create()
    {
        return view('admin.wheelchair-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'daily_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'battery_range_km' => 'required|integer|min:1',
            'max_weight_kg' => 'required|integer|min:1',
            'max_speed_kmh' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        WheelchairType::create($validated);

        return redirect()->route('admin.wheelchair-types.index')
            ->with('success', 'Wheelchair type created successfully.');
    }

    public function edit(WheelchairType $wheelchairType)
    {
        return view('admin.wheelchair-types.edit', compact('wheelchairType'));
    }

    public function update(Request $request, WheelchairType $wheelchairType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'daily_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'battery_range_km' => 'required|integer|min:1',
            'max_weight_kg' => 'required|integer|min:1',
            'max_speed_kmh' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $wheelchairType->update($validated);

        return redirect()->route('admin.wheelchair-types.index')
            ->with('success', 'Wheelchair type updated successfully.');
    }

    public function destroy(WheelchairType $wheelchairType)
    {
        $wheelchairType->delete();
        return redirect()->route('admin.wheelchair-types.index')
            ->with('success', 'Wheelchair type deleted successfully.');
    }
}
