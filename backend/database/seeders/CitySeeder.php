<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name' => 'Mecca',
                'name_ar' => 'مكة المكرمة',
                'country' => 'Saudi Arabia',
                'country_code' => 'SA',
                'latitude' => 21.4225,
                'longitude' => 39.8262,
                'timezone' => 'Asia/Riyadh',
                'is_active' => true,
            ],
            [
                'name' => 'Medina',
                'name_ar' => 'المدينة المنورة',
                'country' => 'Saudi Arabia',
                'country_code' => 'SA',
                'latitude' => 24.5247,
                'longitude' => 39.5692,
                'timezone' => 'Asia/Riyadh',
                'is_active' => true,
            ],
            [
                'name' => 'Jeddah',
                'name_ar' => 'جدة',
                'country' => 'Saudi Arabia',
                'country_code' => 'SA',
                'latitude' => 21.5169,
                'longitude' => 39.2192,
                'timezone' => 'Asia/Riyadh',
                'is_active' => true,
            ],
            [
                'name' => 'Riyadh',
                'name_ar' => 'الرياض',
                'country' => 'Saudi Arabia',
                'country_code' => 'SA',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'timezone' => 'Asia/Riyadh',
                'is_active' => true,
            ],
            [
                'name' => 'Dammam',
                'name_ar' => 'الدمام',
                'country' => 'Saudi Arabia',
                'country_code' => 'SA',
                'latitude' => 26.4207,
                'longitude' => 50.0888,
                'timezone' => 'Asia/Riyadh',
                'is_active' => true,
            ],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['name' => $city['name']],
                $city
            );
        }
    }
}
