<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CsvDataSeeder extends Seeder
{
    public function run(): void
    {
        $basePath = base_path('Tech-Triathlon 2026 - Datasets/data');

        // Seed Outlets
        $this->command->info('Seeding Outlets...');
        if (($handle = fopen($basePath . '/General Data/outlets.csv', 'r')) !== false) {
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                DB::table('outlets')->insert([
                    'outlet_id' => $data['outlet_id'],
                    'brand' => $data['brand'],
                    'district' => $data['district'],
                    'depot' => $data['depot'],
                    'dock_type' => $data['dock_type'],
                    'parking_constraint' => $data['parking_constraint'],
                    'mall_window' => empty($data['mall_window']) ? null : $data['mall_window'],
                    'window_open_time' => $data['window_open_time'],
                    'window_close_time' => $data['window_close_time'],
                ]);
            }
            fclose($handle);
        }

        // Seed Vehicles
        $this->command->info('Seeding Vehicles...');
        if (($handle = fopen($basePath . '/General Data/vehicles.csv', 'r')) !== false) {
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                DB::table('vehicles')->insert([
                    'vehicle_id' => $data['vehicle_id'],
                    'type' => $data['type'],
                    'temp' => $data['temp'],
                    'weight_cap_kg' => (float) $data['weight_cap_kg'],
                    'volume_cap_m3' => (float) $data['volume_cap_m3'],
                    'fuel_type' => $data['fuel_type'],
                    'km_per_l' => (float) $data['km_per_l'],
                    'weekly_fuel_quota_l' => (float) $data['weekly_fuel_quota_l'],
                    'depot' => $data['depot'],
                ]);
            }
            fclose($handle);
        }

        // Seed Test Orders and Trips
        $this->command->info('Seeding Trips & Orders (Test Data)...');
        if (($handle = fopen($basePath . '/Test Data/task1_test_inputs.csv', 'r')) !== false) {
            $header = fgetcsv($handle);
            $tripsMap = []; // Keep track of inserted trips
            $vehicleTripCounts = []; // Track trips per vehicle per day

            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);

                // For the Hackathon we only need a small subset to show the UI working.
                // Let's seed just the first 100 rows to make it fast
                static $count = 0;
                if ($count++ > 100) break;

                $tripKey = $data['route_id'];
                $tripId = null;

                if (!isset($tripsMap[$tripKey])) {
                    $tripId = Str::uuid()->toString();
                    $tripsMap[$tripKey] = $tripId;
                    
                    $vKey = $data['vehicle_id'] . '_' . $data['dispatch_date'];
                    $vehicleTripCounts[$vKey] = isset($vehicleTripCounts[$vKey]) ? $vehicleTripCounts[$vKey] + 1 : 1;

                    DB::table('trips')->insert([
                        'trip_id' => $tripId,
                        'vehicle_id' => $data['vehicle_id'],
                        'trip_number' => $vehicleTripCounts[$vKey],
                        'operation_date' => $data['dispatch_date'],
                        'brand' => $data['brand'],
                        'district' => $data['district'],
                        'status' => 'dispatched', // Let's set some to dispatched so Dispatcher sees them
                        'total_weight_kg' => 0,
                        'total_volume_m3' => 0,
                    ]);
                } else {
                    $tripId = $tripsMap[$tripKey];
                }

                DB::table('orders')->insert([
                    'order_ref' => $data['delivery_id'],
                    'outlet_id' => $data['outlet_id'],
                    'order_date' => $data['order_date'],
                    'temp_requirement' => strtolower($data['temp_requirement']),
                    'order_units' => (int) $data['order_units'],
                    'order_weight_kg' => (float) $data['order_weight_kg'],
                    'order_volume_m3' => (float) $data['order_volume_m3'],
                    'status' => 'allocated',
                    'trip_id' => $tripId,
                ]);

                DB::table('route_legs')->insert([
                    'leg_id' => Str::uuid()->toString(),
                    'trip_id' => $tripId,
                    'seq' => (int) $data['seq_in_route'],
                    'from_point' => 'DEPOT', // Simplified
                    'to_outlet' => $data['outlet_id'],
                    'planned_arrival_time' => $data['dispatch_date'] . ' ' . $data['planned_arrival_time'],
                ]);
            }
            fclose($handle);
        }
    }
}
