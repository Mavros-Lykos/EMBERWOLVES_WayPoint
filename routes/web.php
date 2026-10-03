<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Language Switcher
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'si', 'ta'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('locale.set');

// Authentication Routes (AUTH-01)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/auth/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/magic-login/{role}', [AuthController::class, 'magicLogin'])->name('magic.login');

// Protected Routes
Route::middleware('auth')->group(function () {

    // ── STORE MANAGER (STORE-01 to STORE-06) ─────────────────────────────
    Route::prefix('store')->middleware('role:store_manager')->group(function () {
        Route::get('/dashboard',              [DashboardController::class, 'store'])->name('store.dashboard');
        Route::get('/order/new',               [DashboardController::class, 'storeOrderNew'])->name('store.order.new');
        Route::get('/order/history',           [DashboardController::class, 'storeHistory'])->name('store.history');
        Route::get('/order/{id}',              [DashboardController::class, 'storeOrderTrack'])->name('store.order.track');
        Route::get('/delivery/{id}/receive',   [DashboardController::class, 'storeReceiveView'])->name('store.receive.view');
        Route::get('/delivery/{id}/dispute',   [DashboardController::class, 'storeDisputeView'])->name('store.dispute.view');
        Route::post('/order',                  [DashboardController::class, 'storePlaceOrder'])->name('store.order');
        Route::post('/accept',                 [DashboardController::class, 'storeAccept'])->name('store.accept');
        Route::post('/dispute',                [DashboardController::class, 'storeDispute'])->name('store.dispute');
    });

    // ── DISPATCHER (DISP-01 to DISP-08 + Reports) ────────────────────────
    Route::prefix('dispatch')->middleware('role:dispatcher')->group(function () {
        Route::get('/overview',                [DashboardController::class, 'dispatchOverview'])->name('dispatch.overview');
        Route::get('/live',                    [DashboardController::class, 'dispatchLive'])->name('dispatch.live');
        Route::get('/crisis',                  [DashboardController::class, 'dispatchCrisis'])->name('dispatch.crisis');
        Route::get('/crisis/overload',         [DashboardController::class, 'dispatchCrisis'])->name('dispatch.crisis.overload');
        Route::get('/plan',                    [DashboardController::class, 'dispatchPlan'])->name('dispatch.plan');
        Route::get('/planning',                [DashboardController::class, 'dispatchPlan'])->name('dispatch.planning');
        Route::get('/planning/sequence/{trip_id}', [DashboardController::class, 'dispatchSequenceView'])->name('dispatch.sequence');
        Route::get('/planning/deferral/{order_id}', [DashboardController::class, 'dispatchDeferralView'])->name('dispatch.deferral');
        Route::get('/vehicle/{vehicle_id}',    [DashboardController::class, 'dispatchVehicleView'])->name('dispatch.vehicle');
        Route::get('/emergency/handoff/{trip_id}', [DashboardController::class, 'dispatchEmergencyView'])->name('dispatch.emergency');
        Route::get('/reports',                 [DashboardController::class, 'dispatchReports'])->name('dispatch.reports');
        Route::post('/allocate',               [DashboardController::class, 'dispatchRunAllocation'])->name('dispatch.allocate');
        Route::post('/defer',                  [DashboardController::class, 'dispatchDeferOrder'])->name('dispatch.defer');
        Route::post('/save-plan',              [DashboardController::class, 'dispatchSavePlan'])->name('dispatch.savePlan');
    });

    // ── DOCK LOADER (LOAD-01 to LOAD-06) ─────────────────────────────────
    $loaderRoutes = function () {
        Route::get('/queue',                           [DashboardController::class, 'loaderQueue'])->name('loader.queue');
        Route::get('/inspect/{trip_id}',               [DashboardController::class, 'loaderInspectView'])->name('loader.inspect.view');
        Route::get('/load/{trip_id}',                  [DashboardController::class, 'loaderLoadView'])->name('loader.load.view');
        Route::get('/load/{trip_id}/exception',        [DashboardController::class, 'loaderExceptionView'])->name('loader.exception.view');
        Route::get('/release/{trip_id}',               [DashboardController::class, 'loaderReleaseView'])->name('loader.release.view');
        Route::get('/load/{trip_id}/alert-revision',   [DashboardController::class, 'loaderRevisionView'])->name('loader.revision.view');
        Route::get('/load/{trip_id}/revision',         [DashboardController::class, 'loaderRevisionView']);
        Route::post('/dispatch',                       [DashboardController::class, 'loaderDispatch'])->name('loader.dispatch');
        Route::post('/exception',                      [DashboardController::class, 'loaderException'])->name('loader.exception');
        Route::post('/confirm-item',                   [DashboardController::class, 'loaderConfirmItem'])->name('loader.confirm');
    };
    Route::prefix('loader')->middleware('role:loader')->group($loaderRoutes);
    Route::prefix('depot')->middleware('role:loader')->group($loaderRoutes);

    // ── FIELD DRIVER (DRV-01 to DRV-08) ──────────────────────────────────
    $driverRoutes = function () {
        Route::get('/pti',                     [DashboardController::class, 'driverPTI'])->name('driver.pti');
        Route::get('/route',                   [DashboardController::class, 'driverRoute'])->name('driver.route');
        Route::get('/stop/{leg_id}',           [DashboardController::class, 'driverStopView'])->name('driver.stop.view');
        Route::get('/stop/{leg_id}/pod',       [DashboardController::class, 'driverPodView'])->name('driver.pod.view');
        Route::get('/stop/{leg_id}/exception', [DashboardController::class, 'driverStopExceptionView'])->name('driver.stop.exception');
        Route::get('/break',                   [DashboardController::class, 'driverBreak'])->name('driver.break');
        Route::get('/emergency/breakdown',     [DashboardController::class, 'driverBreakdown'])->name('driver.breakdown');
        Route::get('/breakdown',               [DashboardController::class, 'driverBreakdown'])->name('driver.breakdown.alt');
        Route::get('/trip-end',                [DashboardController::class, 'driverTripEnd'])->name('driver.tripEnd');
        Route::post('/trip-end',               [DashboardController::class, 'driverTripEnd'])->name('driver.tripEnd.post');
        Route::post('/arrival',                [DashboardController::class, 'driverMarkArrival'])->name('driver.arrival');
        Route::post('/confirm-delivery',       [DashboardController::class, 'driverConfirmDelivery'])->name('driver.confirm');
        Route::post('/exception',              [DashboardController::class, 'driverException'])->name('driver.exception');
    };
    Route::prefix('driver')->middleware('role:driver')->group($driverRoutes);
    Route::prefix('field')->middleware('role:driver')->group($driverRoutes);
});

Route::get('/', fn() => redirect()->route('login'));
