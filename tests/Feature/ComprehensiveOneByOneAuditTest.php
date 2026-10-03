<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComprehensiveOneByOneAuditTest extends TestCase
{
    protected $storeUser;
    protected $dispatchUser;
    protected $loaderUser;
    protected $driverUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storeUser = User::where('role', 'store_manager')->first() 
            ?? User::factory()->create(['role' => 'store_manager', 'outlet_id' => 'OUT007']);
        
        $this->dispatchUser = User::where('role', 'dispatcher')->first()
            ?? User::factory()->create(['role' => 'dispatcher', 'depot' => 'Peliyagoda']);

        $this->loaderUser = User::where('role', 'loader')->first()
            ?? User::factory()->create(['role' => 'loader', 'depot' => 'Peliyagoda']);

        $this->driverUser = User::where('role', 'driver')->first()
            ?? User::factory()->create(['role' => 'driver', 'vehicle_id' => 'VEH-003']);
    }

    public function test_auth_and_magic_logins()
    {
        // AUTH-01
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->get('/auth/login');
        $response->assertStatus(200);

        // Magic logins
        $this->get('/magic-login/store_manager')->assertRedirect('/store/dashboard');
        $this->get('/magic-login/dispatcher')->assertRedirect('/dispatch/overview');
        $this->get('/magic-login/loader')->assertRedirect('/loader/queue');
        $this->get('/magic-login/driver')->assertRedirect('/driver/pti');
    }

    public function test_store_manager_screens_and_database_mutations()
    {
        $this->actingAs($this->storeUser);

        // STORE-01: Dashboard
        $this->get('/store/dashboard')->assertStatus(200);

        // STORE-02: New Order Placement View
        $this->get('/store/order/new')->assertStatus(200);

        // STORE-02 POST: Place Order & Database Check
        $orderRef = 'TEST' . strtoupper(Str::random(5));
        $postData = [
            'outlet_id' => $this->storeUser->outlet_id ?? 'OUT007',
            'order_units' => 25,
            'temp_requirement' => 'chilled',
            'urgency' => 1,
        ];
        $res = $this->post('/store/order', $postData);
        $res->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'outlet_id' => $this->storeUser->outlet_id ?? 'OUT007',
            'order_units' => 25,
            'temp_requirement' => 'chilled',
            'status' => 'pending'
        ]);

        $order = Order::where('outlet_id', $this->storeUser->outlet_id ?? 'OUT007')->first();

        // STORE-03: Order Track View
        $this->get("/store/order/{$order->order_ref}")->assertStatus(200);

        // STORE-04: Order History View
        $this->get('/store/order/history')->assertStatus(200);

        // STORE-05: Receive View
        $this->get("/store/delivery/{$order->order_ref}/receive")->assertStatus(200);

        // STORE-05 POST: Confirm Receipt & DB Check
        $this->post('/store/accept', [
            'order_ref' => $order->order_ref,
            'seal_number' => '9942',
            'confirmed_units' => 25
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'order_ref' => $order->order_ref,
            'status' => 'delivered'
        ]);

        // STORE-06: Dispute View
        $this->get("/store/delivery/{$order->order_ref}/dispute")->assertStatus(200);

        // STORE-06 POST: Dispute Submission & DB Check
        $this->post('/store/dispute', [
            'order_ref' => $order->order_ref,
            'dispute_type' => 'Shortage at Dock',
            'notes' => '2 crates short shipped at morning bay'
        ])->assertSessionHas('warning');

        $this->assertDatabaseHas('delivery_confirmations', [
            'discrepancy_note' => '[Shortage at Dock] 2 crates short shipped at morning bay'
        ]);
    }

    public function test_dispatcher_screens_and_database_mutations()
    {
        $this->actingAs($this->dispatchUser);

        // DISP-01: Overview
        $this->get('/dispatch/overview')->assertStatus(200);

        // DISP-02: Master Planning
        $this->get('/dispatch/plan')->assertStatus(200);
        $this->get('/dispatch/planning')->assertStatus(200);

        // Test Trip & Legs
        $trip = Trip::first();
        if ($trip) {
            // DISP-03: Sequencer View
            $this->get("/dispatch/planning/sequence/{$trip->trip_id}")->assertStatus(200);

            // DISP-07: Emergency Handoff View
            $this->get("/dispatch/emergency/handoff/{$trip->trip_id}")->assertStatus(200);
        }

        $order = Order::first();
        if ($order) {
            // DISP-04: Deferral View
            $this->get("/dispatch/planning/deferral/{$order->order_ref}")->assertStatus(200);

            // DISP-04 POST: Defer Order & DB Check
            $this->postJson('/dispatch/defer', [
                'order_ref' => $order->order_ref,
                'reason_code' => 'CAPACITY_EXCEEDED'
            ])->assertJson(['success' => true]);

            $this->assertDatabaseHas('orders', [
                'order_ref' => $order->order_ref,
                'status' => 'deferred',
                'deferral_reason' => 'CAPACITY_EXCEEDED'
            ]);
            $this->assertDatabaseHas('deferral_log', [
                'order_ref' => $order->order_ref,
                'reason_code' => 'CAPACITY_EXCEEDED'
            ]);
        }

        // DISP-05: Live Fleet Map
        $this->get('/dispatch/live')->assertStatus(200);

        // DISP-06: Vehicle Telemetry Drawer
        $vehicle = DB::table('vehicles')->first();
        if ($vehicle) {
            $this->get("/dispatch/vehicle/{$vehicle->vehicle_id}")->assertStatus(200);
        }

        // DISP-08: Crisis Overload / Service Equity Mode
        $this->get('/dispatch/crisis')->assertStatus(200);
        $this->get('/dispatch/crisis/overload')->assertStatus(200);

        // DISP-REP: Reports
        $this->get('/dispatch/reports')->assertStatus(200);
    }

    public function test_dock_loader_screens_and_database_mutations()
    {
        $this->actingAs($this->loaderUser);

        // LOAD-01: Queue (under both loader and depot prefixes)
        $this->get('/loader/queue')->assertStatus(200);
        $this->get('/depot/queue')->assertStatus(200);

        $trip = Trip::first();
        if ($trip) {
            // LOAD-02: Pre-Load Inspection
            $this->get("/loader/inspect/{$trip->trip_id}")->assertStatus(200);
            $this->get("/depot/inspect/{$trip->trip_id}")->assertStatus(200);

            // LOAD-03: Active LIFO Loading
            $this->get("/loader/load/{$trip->trip_id}")->assertStatus(200);
            $this->get("/depot/load/{$trip->trip_id}")->assertStatus(200);

            // LOAD-04: Loading Exception Flag
            $this->get("/loader/load/{$trip->trip_id}/exception")->assertStatus(200);
            $this->get("/depot/load/{$trip->trip_id}/exception")->assertStatus(200);

            // LOAD-05: Security Seal Release
            $this->get("/loader/release/{$trip->trip_id}")->assertStatus(200);
            $this->get("/depot/release/{$trip->trip_id}")->assertStatus(200);

            // LOAD-06: Mid-Load Revision Lockout
            $this->get("/loader/load/{$trip->trip_id}/alert-revision")->assertStatus(200);
            $this->get("/depot/load/{$trip->trip_id}/alert-revision")->assertStatus(200);

            // LOAD-04 POST: Log Shortfall Exception & DB Check
            $order = Order::where('trip_id', $trip->trip_id)->first() ?? Order::first();
            if ($order) {
                $this->post('/loader/exception', [
                    'trip_id' => $trip->trip_id,
                    'order_ref' => $order->order_ref,
                    'quantity' => 18,
                    'reason' => 'Damaged by Forklift at Bay'
                ])->assertSessionHas('warning');

                $this->assertDatabaseHas('loading_exceptions', [
                    'trip_id' => $trip->trip_id,
                    'order_ref' => $order->order_ref,
                    'quantity_actual' => 18,
                    'reason' => 'Damaged by Forklift at Bay'
                ]);
            }
        }
    }

    public function test_driver_screens_and_database_mutations()
    {
        $this->actingAs($this->driverUser);

        // DRV-01: PTI
        $this->get('/driver/pti')->assertStatus(200);
        $this->get('/field/pti')->assertStatus(200);

        // DRV-02: Route
        $this->get('/driver/route')->assertStatus(200);
        $this->get('/field/route')->assertStatus(200);

        // DRV-06: Rest Break
        $this->get('/driver/break')->assertStatus(200);
        $this->get('/field/break')->assertStatus(200);

        // DRV-07: Emergency Breakdown
        $this->get('/driver/breakdown')->assertStatus(200);
        $this->get('/field/emergency/breakdown')->assertStatus(200);

        // DRV-08: Trip End
        $this->get('/driver/trip-end')->assertStatus(200);
        $this->get('/field/trip-end')->assertStatus(200);

        // Legs check
        $leg = DB::table('route_legs')->first();
        if ($leg) {
            // DRV-03: Stop Arrival View
            $this->get("/driver/stop/{$leg->leg_id}")->assertStatus(200);
            $this->get("/field/stop/{$leg->leg_id}")->assertStatus(200);

            // DRV-03 POST: Mark Arrival & DB Check
            $this->postJson('/driver/arrival', ['leg_id' => $leg->leg_id])
                ->assertJson(['success' => true]);

            $this->assertDatabaseNotNull('route_legs', 'actual_arrival_time', ['leg_id' => $leg->leg_id]);

            // DRV-04: PoD View
            $this->get("/driver/stop/{$leg->leg_id}/pod")->assertStatus(200);
            $this->get("/field/stop/{$leg->leg_id}/pod")->assertStatus(200);

            // DRV-04 POST: Confirm Delivery PoD & DB Check
            $this->postJson('/driver/confirm-delivery', [
                'trip_id' => $leg->trip_id,
                'leg_id' => $leg->leg_id,
                'signature' => 'MOCK_SIGNATURE_OK',
                'confirmed_units' => 40
            ])->assertJson(['success' => true]);

            $this->assertDatabaseHas('delivery_confirmations', [
                'route_leg_id' => $leg->leg_id,
                'signature_data' => 'MOCK_SIGNATURE_OK'
            ]);

            // DRV-05: Exception View
            $this->get("/driver/stop/{$leg->leg_id}/exception")->assertStatus(200);
            $this->get("/field/stop/{$leg->leg_id}/exception")->assertStatus(200);

            // DRV-05 POST: Exception Log & DB Check
            $this->post('/driver/exception', [
                'leg_id' => $leg->leg_id,
                'reason' => 'Canopy Gate Locked & Guard Absent'
            ])->assertSessionHas('warning');

            $this->assertDatabaseHas('delivery_confirmations', [
                'route_leg_id' => $leg->leg_id,
                'discrepancy_note' => '[EXCEPTION] Canopy Gate Locked & Guard Absent'
            ]);
        }
    }

    protected function assertDatabaseNotNull(string $table, string $column, array $conditions = [])
    {
        $query = DB::table($table);
        foreach ($conditions as $k => $v) {
            $query->where($k, $v);
        }
        $row = $query->first();
        $this->assertNotNull($row, "Record not found in {$table}");
        $this->assertNotNull($row->$column, "Column {$column} is null in {$table}");
    }
}
