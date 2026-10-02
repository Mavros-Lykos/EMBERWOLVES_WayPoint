<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // For Phase 1: Create the 4 essential personas

        User::factory()->create([
            'name' => 'Priya K.',
            'email' => 'priya@waypoint.lk',
            'password' => Hash::make('priya2026'),
            'role' => 'store_manager',
            'outlet_id' => 'OUT007',
        ]);

        User::factory()->create([
            'name' => 'Kamal Lead',
            'email' => 'kamal@waypoint.lk',
            'password' => Hash::make('kamal2026'),
            'role' => 'dispatcher',
            'depot' => 'Peliyagoda',
        ]);

        User::factory()->create([
            'name' => 'Nuwan B.',
            'email' => 'nuwan@waypoint.lk',
            'password' => Hash::make('nuwan2026'),
            'role' => 'loader',
            'depot' => 'Peliyagoda',
        ]);

        User::factory()->create([
            'name' => 'Saman K.',
            'email' => 'saman@waypoint.lk',
            'password' => Hash::make('saman2026'),
            'role' => 'driver',
            'vehicle_id' => 'VEH003',
        ]);
        
        // CSV seeding will be added later for the S1 scenario
        $this->call(CsvDataSeeder::class);
    }
}
