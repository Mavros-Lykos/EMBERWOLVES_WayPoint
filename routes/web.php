<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Language Switcher
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'si', 'ta'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.set');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Hackathon Magic Login for Judges
Route::get('/magic-login/{role}', [AuthController::class, 'magicLogin'])->name('magic.login');

// Protected Routes
Route::middleware('auth')->group(function () {
    
    // Store Manager Routes
    Route::prefix('store')->middleware('role:store_manager')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'store'])->name('store.dashboard');
    });

    // Dispatcher Routes
    Route::prefix('dispatch')->middleware('role:dispatcher')->group(function () {
        Route::get('/overview', [\App\Http\Controllers\DashboardController::class, 'dispatchOverview'])->name('dispatch.overview');
    });

    // Loader Routes
    Route::prefix('loader')->middleware('role:loader')->group(function () {
        Route::get('/queue', [\App\Http\Controllers\DashboardController::class, 'loaderQueue'])->name('loader.queue');
    });

    // Driver Routes
    Route::prefix('driver')->middleware('role:driver')->group(function () {
        Route::get('/route', [\App\Http\Controllers\DashboardController::class, 'driverRoute'])->name('driver.route');
    });
});

// Root redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});
