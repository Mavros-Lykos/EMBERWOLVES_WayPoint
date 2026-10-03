<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Outlet;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HackathonWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed some basic reference data required for the system
        DB::table('users')->insert([
            'name' => 'Store Manager',
            'email' => 'store@test.com',
            'password' => bcrypt('password'),
            'role' => 'store_manager',
            'outlet_id' => 'OUT001',
        ]);

        DB::table('users')->insert([
            'name' => 'Dispatcher',
            'email' => 'dispatch@test.com',
            'password' => bcrypt('password'),
            'role' => 'dispatcher',
            'depot' => 'Peliyagoda',
        ]);

        Outlet::insert([
            ['outlet_id' => 'OUT001', 'district' => 'Colombo', 'depot' => 'Peliyagoda', 'brand' => 'Fresh', 'dock_type' => 'rear_dock', 'parking_constraint' => 'normal', 'window_open_time' => '06:00', 'window_close_time' => '22:00'],
            ['outlet_id' => 'OUT002', 'district' => 'Gampaha', 'depot' => 'Peliyagoda', 'brand' => 'Fresh', 'dock_type' => 'rear_dock', 'parking_constraint' => 'normal', 'window_open_time' => '06:00', 'window_close_time' => '22:00'],
        ]);


        Vehicle::insert([
            ['vehicle_id' => 'V-TEST-01', 'type' => 'truck', 'temp' => 'ambient', 'volume_cap_m3' => 20, 'weight_cap_kg' => 5000, 'depot' => 'Peliyagoda', 'fuel_type' => 'diesel', 'km_per_l' => 10, 'weekly_fuel_quota_l' => 100],
            ['vehicle_id' => 'V-TEST-02', 'type' => 'van', 'temp' => 'reefer', 'volume_cap_m3' => 5, 'weight_cap_kg' => 1200, 'depot' => 'Peliyagoda', 'fuel_type' => 'diesel', 'km_per_l' => 10, 'weekly_fuel_quota_l' => 100],
        ]);
    }

    public function test_store_manager_can_place_order()
    {
        $user = User::where('email', 'store@test.com')->first();
        
        // Mock time to be before cutoff (16:00)
        Carbon::setTestNow(Carbon::today()->setTime(10, 0));

        $response = $this->actingAs($user)->post('/store/order', [
            'outlet_id'        => 'OUT001',
            'order_units'      => 100,
            'temp_requirement' => 'ambient',
            'urgency'          => 1,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'outlet_id' => 'OUT001',
            'order_units' => 100,
            'temp_requirement' => 'ambient',
            'status' => 'pending',
            'urgency_flag' => 1,
        ]);
    }

    public function test_store_manager_cannot_place_order_after_cutoff()
    {
        $user = User::where('email', 'store@test.com')->first();
        
        // Mock time to be AFTER cutoff (17:00)
        Carbon::setTestNow(Carbon::today()->setTime(17, 0));

        $response = $this->actingAs($user)->post('/store/order', [
            'outlet_id'        => 'OUT001',
            'order_units'      => 100,
            'temp_requirement' => 'ambient',
        ]);

        $response->assertSessionHas('error', 'Order cutoff (16:00) has passed. Your order will be placed for tomorrow.');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_allocation_engine_creates_trips_for_pending_orders()
    {
        $dispatcher = User::where('email', 'dispatch@test.com')->first();
        
        // Create 2 pending orders
        Order::insert([
            ['order_ref' => 'ORD1', 'outlet_id' => 'OUT001', 'order_date' => now()->toDateString(), 'temp_requirement' => 'ambient', 'order_units' => 10, 'order_weight_kg' => 24, 'order_volume_m3' => 0.35, 'status' => 'pending', 'urgency_flag' => 0, 'placed_at' => now()],
            ['order_ref' => 'ORD2', 'outlet_id' => 'OUT002', 'order_date' => now()->toDateString(), 'temp_requirement' => 'reefer', 'order_units' => 20, 'order_weight_kg' => 48, 'order_volume_m3' => 0.70, 'status' => 'pending', 'urgency_flag' => 0, 'placed_at' => now()],
        ]);

        $response = $this->actingAs($dispatcher)->postJson('/dispatch/allocate', [
            'date' => now()->toDateString()
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'served' => 2,
        ]);

        // Check if orders are allocated
        $this->assertDatabaseHas('orders', ['order_ref' => 'ORD1', 'status' => 'allocated']);
        $this->assertDatabaseHas('orders', ['order_ref' => 'ORD2', 'status' => 'allocated']);

        // Check if trips are created
        $this->assertTrue(Trip::count() > 0);
    }

    public function test_allocation_engine_defers_when_capacity_exceeded()
    {
        $dispatcher = User::where('email', 'dispatch@test.com')->first();
        
        // Create a massive order that exceeds all truck capacities
        Order::insert([
            ['order_ref' => 'ORD_HUGE', 'outlet_id' => 'OUT001', 'order_date' => now()->toDateString(), 'temp_requirement' => 'ambient', 'order_units' => 50000, 'order_weight_kg' => 120000, 'order_volume_m3' => 1000, 'status' => 'pending', 'urgency_flag' => 0, 'placed_at' => now()],
        ]);

        $response = $this->actingAs($dispatcher)->postJson('/dispatch/allocate', [
            'date' => now()->toDateString()
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'served' => 0,
            'deferred' => 1,
        ]);

        $this->assertDatabaseHas('orders', ['order_ref' => 'ORD_HUGE', 'status' => 'deferred', 'deferral_reason' => 'VOLUME_CAPACITY']);
    }

    public function test_loader_cannot_dispatch_if_items_pending()
    {
        $loader = User::factory()->create(['role' => 'loader', 'depot' => 'Peliyagoda']);
        
        // Mock a trip with pending legs
        $tripId = \Illuminate\Support\Str::uuid();
        Trip::insert([
            'trip_id' => $tripId, 'vehicle_id' => 'V-TEST-01', 'trip_number' => 1, 'operation_date' => now()->toDateString(), 'status' => 'planned', 'depot' => 'Peliyagoda'
        ]);

        DB::table('route_legs')->insert([
            'leg_id' => \Illuminate\Support\Str::uuid(), 'trip_id' => $tripId, 'seq' => 1, 'to_outlet' => 'OUT001', 'status' => 'pending'
        ]);

        $response = $this->actingAs($loader)->post('/loader/dispatch', [
            'trip_id' => $tripId,
            'seal_number' => 'SEAL-123',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('trips', ['trip_id' => $tripId, 'status' => 'planned']);
    }

    public function test_loader_can_dispatch_if_all_items_loaded()
    {
        $loader = User::factory()->create(['role' => 'loader', 'depot' => 'Peliyagoda']);
        
        // Mock a trip with loaded legs
        $tripId = \Illuminate\Support\Str::uuid();
        Trip::insert([
            'trip_id' => $tripId, 'vehicle_id' => 'V-TEST-01', 'trip_number' => 1, 'operation_date' => now()->toDateString(), 'status' => 'planned', 'depot' => 'Peliyagoda'
        ]);

        DB::table('route_legs')->insert([
            'leg_id' => \Illuminate\Support\Str::uuid(), 'trip_id' => $tripId, 'seq' => 1, 'to_outlet' => 'OUT001', 'status' => 'loaded'
        ]);

        $response = $this->actingAs($loader)->post('/loader/dispatch', [
            'trip_id' => $tripId,
            'seal_number' => 'SEAL-123',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('trips', ['trip_id' => $tripId, 'status' => 'dispatched']);
    }
}
