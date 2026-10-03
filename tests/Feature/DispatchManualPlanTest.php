<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DispatchManualPlanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        DB::table('users')->insert([
            'name' => 'Dispatcher Kamal',
            'email' => 'kamal@test.com',
            'password' => bcrypt('password'),
            'role' => 'dispatcher',
            'depot' => 'Peliyagoda'
        ]);
        
        DB::table('outlets')->insert([
            'outlet_id' => 'OUT001', 'brand' => 'Fresh', 'district' => 'Colombo', 'depot' => 'Peliyagoda', 'dock_type' => 'rear_dock', 'parking_constraint' => 'normal', 'window_open_time' => '06:00', 'window_close_time' => '22:00'
        ]);
        
        DB::table('vehicles')->insert([
            'vehicle_id' => 'VEH-D-1', 'type' => 'van', 'temp' => 'chilled', 'weight_cap_kg' => 2000, 'volume_cap_m3' => 5, 'fuel_type' => 'diesel', 'km_per_l' => 10, 'weekly_fuel_quota_l' => 100, 'depot' => 'Peliyagoda'
        ]);
    }

    public function test_dispatcher_can_save_manual_plan_via_drag_and_drop()
    {
        $dispatcher = User::where('email', 'kamal@test.com')->first();
        
        Order::insert([
            'order_ref' => 'ORD-DRAG-01', 'outlet_id' => 'OUT001', 'order_date' => now()->toDateString(),
            'temp_requirement' => 'chilled', 'order_units' => 10, 'status' => 'pending'
        ]);

        Order::insert([
            'order_ref' => 'ORD-DRAG-02', 'outlet_id' => 'OUT001', 'order_date' => now()->toDateString(),
            'temp_requirement' => 'chilled', 'order_units' => 15, 'status' => 'pending'
        ]);

        $response = $this->actingAs($dispatcher)->postJson('/dispatch/save-plan', [
            'allocations' => [
                'VEH-D-1' => ['ORD-DRAG-01', 'ORD-DRAG-02']
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        // Assert Trip Created
        $this->assertDatabaseHas('trips', [
            'vehicle_id' => 'VEH-D-1',
            'status' => 'planned'
        ]);
        
        $trip = DB::table('trips')->where('vehicle_id', 'VEH-D-1')->first();
        
        // Assert Orders Allocated
        $this->assertDatabaseHas('orders', [
            'order_ref' => 'ORD-DRAG-01',
            'status' => 'allocated',
            'trip_id' => $trip->trip_id
        ]);
        
        // Assert Route Legs created sequentially
        $this->assertDatabaseHas('route_legs', [
            'trip_id' => $trip->trip_id,
            'seq' => 1
        ]);
        $this->assertDatabaseHas('route_legs', [
            'trip_id' => $trip->trip_id,
            'seq' => 2
        ]);
    }

    public function test_dispatcher_can_manually_defer_order()
    {
        $dispatcher = User::where('email', 'kamal@test.com')->first();
        
        Order::insert([
            'order_ref' => 'ORD-DEFER-01', 'outlet_id' => 'OUT001', 'order_date' => now()->toDateString(),
            'temp_requirement' => 'chilled', 'order_units' => 10, 'status' => 'pending'
        ]);

        $response = $this->actingAs($dispatcher)->post('/dispatch/defer', [
            'order_ref' => 'ORD-DEFER-01',
            'reason_code' => 'CAP-EXCEED'
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('orders', [
            'order_ref' => 'ORD-DEFER-01',
            'status' => 'deferred',
            'deferral_reason' => 'CAP-EXCEED'
        ]);
        
        $this->assertDatabaseHas('deferral_log', [
            'order_ref' => 'ORD-DEFER-01',
            'reason_code' => 'CAP-EXCEED'
        ]);
    }
}
