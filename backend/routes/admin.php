<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WheelchairController;
use App\Http\Controllers\Admin\WheelchairTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
*/

// Login routes (accessible to guests)
Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');

// Authenticated admin routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Resource routes
    Route::resource('stations', StationController::class)->names('admin.stations');
    Route::resource('wheelchair-types', WheelchairTypeController::class)->names('admin.wheelchair-types');
    Route::resource('wheelchairs', WheelchairController::class)->names('admin.wheelchairs');
    Route::resource('bookings', BookingController::class)->names('admin.bookings');
    Route::resource('users', UserController::class)->names('admin.users');
    
    // Settings routes
    Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('admin.settings.update');
    
    // Global search
    Route::get('search', [DashboardController::class, 'search'])->name('admin.search');
});

