<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\WheelchairController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - MobilityKSA Public Frontend
|--------------------------------------------------------------------------
*/

// Public Routes (no authentication required)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/set-location', [HomeController::class, 'setLocation'])->name('home.setLocation');

// Wheelchair browsing
Route::get('/wheelchairs', [WheelchairController::class, 'index'])->name('wheelchairs.index');
Route::get('/wheelchairs/{id}', [WheelchairController::class, 'show'])->name('wheelchairs.show');

// Web Frontend Auth Routes (using /auth prefix to separate from admin)
Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('web.login');
    Route::post('/login', [AuthController::class, 'login'])->name('web.login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('web.register');
    Route::post('/register', [AuthController::class, 'register'])->name('web.register.submit');
});

// Protected Routes (authentication required)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('web.logout');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{wheelchair}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Payment
    Route::get('/payment/{booking}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Default login route (for auth middleware redirect - redirects to web frontend login)
Route::get('/login', function () {
    return redirect()->route('web.login');
})->name('login');

// Admin routes (separate from web frontend)
Route::prefix('admin')->group(function () {
    require base_path('routes/admin.php');
});
