<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExceptionHandlingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        DB::table('users')->insert([
            'name' => 'Driver User',
            'email' => 'driver@test.com',
            'password' => bcrypt('password'),
            'role' => 'driver',
            'vehicle_id' => 'V-TEST-01',
        ]);
    }

    public function test_driver_can_log_exception_for_route_leg()
    {
        $driver = User::where('email', 'driver@test.com')->first();
        
        $tripId = Str::uuid();
        Trip::insert([
            'trip_id' => $tripId, 'vehicle_id' => 'V-TEST-01', 'trip_number' => 1, 'operation_date' => now()->toDateString(), 'status' => 'dispatched', 'depot' => 'Peliyagoda'
        ]);

        $legId = Str::uuid();
        DB::table('route_legs')->insert([
            'leg_id' => $legId, 'trip_id' => $tripId, 'seq' => 1, 'to_outlet' => 'OUT001', 'status' => 'loaded'
        ]);

        $response = $this->actingAs($driver)->post('/driver/exception', [
            'leg_id' => $legId,
            'reason' => 'Outlet Closed',
        ]);

        $response->assertSessionHas('warning', 'Exception logged: Outlet Closed. Dispatcher notified.');
        
        $this->assertDatabaseHas('delivery_confirmations', [
            'route_leg_id' => $legId,
            'discrepancy_note' => '[EXCEPTION] Outlet Closed'
        ]);
    }

    public function test_driver_exception_fails_validation_without_leg_id()
    {
        $driver = User::where('email', 'driver@test.com')->first();
        
        // Simulating the bug we fixed where leg_id was missing/null and caused DB crash
        $response = $this->actingAs($driver)->post('/driver/exception', [
            'reason' => 'Outlet Closed',
            // leg_id intentionally missing
        ]);

        // It should throw a validation error rather than a 500 DB exception
        $response->assertSessionHasErrors(['leg_id']);
        $this->assertDatabaseCount('delivery_confirmations', 0);
    }
}
