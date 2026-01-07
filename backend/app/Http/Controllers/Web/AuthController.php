<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('web.auth.login');
    }

    /**
     * Handle login (simplified - no OTP).
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'country_code' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Combine country code with phone for lookup
        $fullPhone = $validated['country_code'] . $validated['phone'];

        // Find user by phone (try both with and without country code)
        $user = User::where('phone', $fullPhone)
            ->orWhere('phone', $validated['phone'])
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['phone' => 'Invalid phone number or password.'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->intended(route('home'))
            ->with('success', 'Welcome back!');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('web.auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'identity_type' => 'required|in:national_id,passport,iqama',
            'identity_number' => 'required|string|max:50',
        ]);

        // Combine country code with phone
        $fullPhone = $validated['country_code'] . $validated['phone'];

        // Check if phone already exists
        if (User::where('phone', $fullPhone)->exists()) {
            return back()->withErrors(['phone' => 'This phone number is already registered.'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $fullPhone,
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'identity_type' => $validated['identity_type'],
            'identity_number' => $validated['identity_number'],
            'language' => 'en',
            'status' => 'active',
            'verification_status' => 'pending',
        ]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Account created successfully! Welcome to MobilityKSA.');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out.');
    }
}
