<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Default login route (for auth middleware redirect)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Admin routes
Route::prefix('admin')->group(function () {
    require base_path('routes/admin.php');
});

