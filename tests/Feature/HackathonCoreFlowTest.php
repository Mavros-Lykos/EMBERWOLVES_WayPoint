<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Trip;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\RouteLeg;
use App\Models\Outlet;

class HackathonCoreFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed basic necessary models for testing
        $vehicle = Vehicle::factory()->create(['vehicle_id' => 'TEST_VEH1', 'type' => 'Van', 'temp' => 'ambient']);
        $outlet = Outlet::factory()->create(['outlet_id' => 'TEST_OUTLET']);
        
        $trip = Trip::factory()->create([
            'trip_id' => 'TEST_TRIP1',
            'vehicle_id' => 'TEST_VEH1',
            'status' => 'planned'
        ]);

        Order::factory()->create([
            'order_ref' => 'TEST_ORD1',
            'trip_id' => 'TEST_TRIP1',
            'outlet_id' => 'TEST_OUTLET',
            'status' => 'allocated'
        ]);

        RouteLeg::factory()->create([
            'trip_id' => 'TEST_TRIP1',
            'to_outlet' => 'TEST_OUTLET',
            'seq' => 1
        ]);
    }

    public function test_loader_can_dispatch_trip()
    {
        $loader = User::factory()->create(['role' => 'loader']);
        
        $response = $this->actingAs($loader)->post(route('loader.dispatch'), [
            'trip_id' => 'TEST_TRIP1'
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('trips', [
            'trip_id' => 'TEST_TRIP1',
            'status' => 'dispatched'
        ]);
    }

    public function test_driver_can_confirm_delivery()
    {
        $driver = User::factory()->create(['role' => 'driver', 'vehicle_id' => 'TEST_VEH1']);
        
        // Set trip to dispatched so driver can see it
        Trip::where('trip_id', 'TEST_TRIP1')->update(['status' => 'dispatched']);

        $response = $this->actingAs($driver)->postJson(route('driver.confirm'), [
            'trip_id' => 'TEST_TRIP1',
            'signature' => 'data:image/png;base64,123456',
            'lat' => 6.9,
            'lng' => 79.8
        ]);

        $response->assertStatus(200);
        
        // Order should be delivered
        $this->assertDatabaseHas('orders', [
            'order_ref' => 'TEST_ORD1',
            'status' => 'delivered'
        ]);
        
        // Delivery confirmation should be logged
        $this->assertDatabaseHas('delivery_confirmations', [
            'signature_data' => 'data:image/png;base64,123456'
        ]);
    }

    public function test_store_manager_can_dispute_order()
    {
        $storeManager = User::factory()->create(['role' => 'store_manager']);
        
        $response = $this->actingAs($storeManager)->post(route('store.dispute'), [
            'order_ref' => 'TEST_ORD1'
        ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('orders', [
            'order_ref' => 'TEST_ORD1',
            'status' => 'deferred',
            'deferral_reason' => 'damaged'
        ]);

        $this->assertDatabaseHas('deferral_log', [
            'order_ref' => 'TEST_ORD1',
            'reason_code' => 'damaged'
        ]);
    }
}
