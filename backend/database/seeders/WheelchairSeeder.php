<?php

namespace Database\Seeders;

use App\Models\Wheelchair;
use App\Models\WheelchairType;
use App\Models\Station;
use Illuminate\Database\Seeder;

class WheelchairSeeder extends Seeder
{
    /**
     * Create sample wheelchairs distributed across stations.
     */
    public function run(): void
    {
        $types = WheelchairType::all();
        $stations = Station::all();

        $brands = [
            'MobilityPro' => ['MK-Standard', 'MK-Premium', 'MK-Heavy', 'MK-Travel', 'MK-Terrain'],
            'WheelEase' => ['WE-100', 'WE-200', 'WE-300', 'WE-Lite', 'WE-Max'],
            'FreedomChair' => ['FC-Basic', 'FC-Plus', 'FC-Elite', 'FC-Go', 'FC-Adventure'],
            'CarePlus' => ['CP-Comfort', 'CP-Deluxe', 'CP-Ultra', 'CP-Mini', 'CP-Rugged'],
        ];

        $codePrefix = 'WC';
        $counter = 1;

        foreach ($stations as $station) {
            // Create 3-8 wheelchairs per station
            $wheelchairCount = rand(3, 8);
            
            for ($i = 0; $i < $wheelchairCount; $i++) {
                $type = $types->random();
                $brandName = array_rand($brands);
                $model = $brands[$brandName][array_rand($brands[$brandName])];
                
                // Random status with most being available
                $statusOptions = ['available', 'available', 'available', 'available', 'rented', 'maintenance'];
                $status = $statusOptions[array_rand($statusOptions)];
                
                // Battery capacity based on status
                $battery = match($status) {
                    'available' => rand(80, 100),
                    'rented' => rand(30, 90),
                    'maintenance' => rand(0, 50),
                    default => 100,
                };

                $lastMaintenance = now()->subDays(rand(7, 90));
                $nextMaintenance = $lastMaintenance->copy()->addDays(90);

                Wheelchair::create([
                    'code' => $codePrefix . '-' . str_pad($counter++, 3, '0', STR_PAD_LEFT),
                    'wheelchair_type_id' => $type->id,
                    'station_id' => $station->id,
                    'brand' => $brandName,
                    'model' => $model,
                    'battery_capacity' => $battery,
                    'status' => $status,
                    'last_maintenance' => $lastMaintenance,
                    'next_maintenance' => $nextMaintenance,
                    'notes' => $status === 'maintenance' ? 'Under routine maintenance check' : null,
                ]);
            }
        }
    }
}
