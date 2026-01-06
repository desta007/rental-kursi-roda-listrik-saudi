<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WheelchairController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes - MobilityKSA Electric Wheelchair Rental
|--------------------------------------------------------------------------
*/

// API Version 1
Route::prefix('v1')->group(function () {
    
    // Public routes (no authentication required)
    Route::prefix('auth')->group(function () {
        Route::post('/request-otp', [AuthController::class, 'requestOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    });

    // Public wheelchair and station browsing
    Route::get('/wheelchair-types', [WheelchairController::class, 'types']);
    Route::get('/wheelchairs', [WheelchairController::class, 'index']);
    Route::get('/wheelchairs/{id}', [WheelchairController::class, 'show']);
    Route::get('/wheelchairs/{id}/availability', [WheelchairController::class, 'checkAvailability']);
    Route::get('/stations', [StationController::class, 'index']);
    Route::get('/stations/{id}', [StationController::class, 'show']);
    
    // Stripe webhook (no auth)
    Route::post('/payments/webhook', [PaymentController::class, 'webhook']);

    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Auth
        Route::prefix('auth')->group(function () {
            Route::post('/register', [AuthController::class, 'register']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        // Profile
        Route::prefix('profile')->group(function () {
            Route::put('/', [AuthController::class, 'register']); // Same as register for updates
            Route::post('/upload-identity', [AuthController::class, 'uploadIdentity']);
        });

        // Bookings
        Route::prefix('bookings')->group(function () {
            Route::get('/', [BookingController::class, 'index']);
            Route::post('/', [BookingController::class, 'store']);
            Route::get('/{id}', [BookingController::class, 'show']);
            Route::post('/{id}/cancel', [BookingController::class, 'cancel']);
        });

        // Payments
        Route::prefix('payments')->group(function () {
            Route::post('/initiate', [PaymentController::class, 'initiate']);
            Route::post('/confirm', [PaymentController::class, 'confirm']);
            Route::get('/{id}', [PaymentController::class, 'status']);
        });
    });
});
