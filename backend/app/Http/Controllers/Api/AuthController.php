<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Request OTP via email.
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            // Create new user with pending status
            $user = User::create([
                'name' => '',
                'phone' => '',
                'email' => $request->email,
                'status' => 'pending',
            ]);
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::raw("Your MobilityKSA verification code is: {$otp}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('MobilityKSA - Verification Code');
        });

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email',
            'is_new_user' => $user->status === 'pending',
        ]);
    }

    /**
     * Verify OTP and login.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if (!$user->isOtpValid($request->otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 400);
        }

        // Clear OTP
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        // Create token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'is_profile_complete' => !empty($user->name) && !empty($user->phone),
            ],
        ]);
    }

    /**
     * Complete registration / update profile.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'language' => 'sometimes|in:en,ar,id',
        ]);

        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'language' => $request->language ?? 'en',
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ]);
    }

    /**
     * Get current user.
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Upload identity document.
     */
    public function uploadIdentity(Request $request)
    {
        $request->validate([
            'identity_type' => 'required|in:passport,iqama,national_id',
            'identity_number' => 'required|string|max:50',
            'identity_photo' => 'required|image|max:5120', // 5MB max
        ]);

        $user = $request->user();
        
        $path = $request->file('identity_photo')->store('identity-documents', 'public');

        $user->update([
            'identity_type' => $request->identity_type,
            'identity_number' => $request->identity_number,
            'identity_photo' => $path,
            'verification_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Identity document uploaded. Verification pending.',
            'data' => $user,
        ]);
    }
}
