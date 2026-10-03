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

        // Seed Pending Orders from Peak Day Scenario for testing Allocation Engine
        $this->command->info('Seeding Pending Orders (S1 Peak Day Scenario)...');
        if (($handle = fopen($basePath . '/Test Data/task2b_peak_day_scenarios.csv', 'r')) !== false) {
            $header = fgetcsv($handle);
            $today = now()->toDateString();
            
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                
                // Only seed S1 scenario
                if ($data['scenario'] !== 'S1') continue;

                DB::table('orders')->insert([
                    'order_ref' => $data['order_ref'],
                    'outlet_id' => $data['outlet_id'],
                    'order_date' => $today,
                    'placed_at' => now()->subHours(rand(1, 12)),
                    'temp_requirement' => strtolower($data['temp_requirement']),
                    'order_units' => (int) $data['order_units'],
                    'order_weight_kg' => (float) $data['order_weight_kg'],
                    'order_volume_m3' => (float) $data['order_volume_m3'],
                    'status' => 'pending',
                    'deferred_yesterday' => (bool) $data['deferred_yesterday'],
                    'days_since_last_served' => (int) $data['days_since_last_served'],
                    'urgency_flag' => (bool) $data['deferred_yesterday'] || ((int) $data['days_since_last_served'] > 2),
                    'trip_id' => null,
                ]);
            }
            fclose($handle);
        }
    }
}
