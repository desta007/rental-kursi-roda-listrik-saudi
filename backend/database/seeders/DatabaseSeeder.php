<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order matters: stations and types first, then wheelchairs, users, then bookings.
     */
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            StationSeeder::class,
            WheelchairTypeSeeder::class,
            WheelchairSeeder::class,
            UserSeeder::class,
            BookingSeeder::class,
            SettingSeeder::class,
        ]);

        $this->command->info('✅ MobilityKSA database seeded successfully!');
        $this->command->info('📍 10 stations in Mecca');
        $this->command->info('🦽 5 wheelchair types');
        $this->command->info('👥 12 sample users');
        $this->command->info('📋 10 sample bookings');
    }
}
