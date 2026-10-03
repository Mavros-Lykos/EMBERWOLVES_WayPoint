<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

echo "Seeding route_legs for all existing trips...\n";

$trips = DB::table('trips')->get();
$totalLegs = 0;

foreach ($trips as $trip) {
    $orders = DB::table('orders')->where('trip_id', $trip->trip_id)->get();
    $vehicle = DB::table('vehicles')->where('vehicle_id', $trip->vehicle_id)->first();
    $isReefer = $vehicle && $vehicle->temp === 'reefer';
    $depot = $vehicle->depot ?? 'Peliyagoda';

    if ($orders->isEmpty()) {
        // If trip has no orders, assign 2-3 sample outlets from the trip district
        $sampleOutlets = DB::table('outlets')->where('district', $trip->district)->take(3)->get();
        if ($sampleOutlets->isEmpty()) {
            $sampleOutlets = DB::table('outlets')->take(3)->get();
        }
        $prevPoint = $depot;
        foreach ($sampleOutlets as $idx => $outlet) {
            $seq = $idx + 1;
            DB::table('route_legs')->insert([
                'leg_id' => (string) Str::uuid(),
                'trip_id' => $trip->trip_id,
                'seq' => $seq,
                'from_point' => $prevPoint,
                'to_outlet' => $outlet->outlet_id,
                'planned_depart_time' => Carbon::parse($trip->operation_date . ' 04:00:00')->addMinutes($idx * 45),
                'planned_arrival_time' => Carbon::parse($trip->operation_date . ' 04:30:00')->addMinutes($idx * 45),
                'distance_km' => 12.5 + ($idx * 4),
                'reefer_temp_celsius' => $isReefer ? 3.1 : null,
            ]);
            $prevPoint = $outlet->outlet_id;
            $totalLegs++;
        }
    } else {
        $prevPoint = $depot;
        foreach ($orders as $idx => $order) {
            $seq = $idx + 1;
            DB::table('route_legs')->insert([
                'leg_id' => (string) Str::uuid(),
                'trip_id' => $trip->trip_id,
                'seq' => $seq,
                'from_point' => $prevPoint,
                'to_outlet' => $order->outlet_id,
                'planned_depart_time' => Carbon::parse($trip->operation_date . ' 04:00:00')->addMinutes($idx * 45),
                'planned_arrival_time' => Carbon::parse($trip->operation_date . ' 04:30:00')->addMinutes($idx * 45),
                'distance_km' => 14.0 + ($idx * 3),
                'reefer_temp_celsius' => $isReefer ? 2.9 : null,
            ]);
            $prevPoint = $order->outlet_id;
            $totalLegs++;
        }
    }
}

echo "Successfully seeded $totalLegs route_legs across " . count($trips) . " trips.\n";
echo "Current route_legs count: " . DB::table('route_legs')->count() . "\n";
