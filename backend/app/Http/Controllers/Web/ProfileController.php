<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display user profile.
     */
    public function show()
    {
        $user = auth()->user();

        // Get booking stats
        $totalBookings = $user->bookings()->count();
        $activeBookings = $user->bookings()->whereIn('status', ['confirmed', 'active'])->count();
        $completedBookings = $user->bookings()->where('status', 'completed')->count();

        return view('web.profile.index', compact('user', 'totalBookings', 'activeBookings', 'completedBookings'));
    }

    /**
     * Show edit profile form.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('web.profile.edit', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'identity_type' => 'nullable|in:national_id,passport,iqama',
            'identity_number' => 'nullable|string|max:50',
            'language' => 'required|in:en,ar',
        ]);

        auth()->user()->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
