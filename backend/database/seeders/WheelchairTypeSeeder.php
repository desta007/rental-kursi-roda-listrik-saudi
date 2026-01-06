<?php

namespace Database\Seeders;

use App\Models\WheelchairType;
use Illuminate\Database\Seeder;

class WheelchairTypeSeeder extends Seeder
{
    /**
     * Real wheelchair types with Saudi pricing in SAR.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Standard Electric Wheelchair',
                'name_ar' => 'كرسي متحرك كهربائي عادي',
                'description' => 'Basic electric wheelchair suitable for flat surfaces. Ideal for everyday use in malls, hotels, and paved areas around the Holy Mosque.',
                'description_ar' => 'كرسي متحرك كهربائي أساسي مناسب للأسطح المستوية. مثالي للاستخدام اليومي في المولات والفنادق والمناطق المرصوفة حول المسجد الحرام.',
                'daily_rate' => 100.00,
                'weekly_rate' => 500.00,
                'monthly_rate' => 1500.00,
                'deposit_amount' => 500.00,
                'battery_range_km' => 20,
                'max_weight_kg' => 100,
                'max_speed_kmh' => 6,
                'features' => json_encode([
                    'Joystick control',
                    'Foldable design',
                    'LED headlight',
                    'USB charging port',
                    'Anti-tip wheels',
                ]),
                'specifications' => json_encode([
                    'motor' => '250W brushless',
                    'battery' => '24V 12Ah Lithium',
                    'charging_time' => '4-6 hours',
                    'seat_width' => '45cm',
                    'weight' => '25kg',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Premium Electric Wheelchair',
                'name_ar' => 'كرسي متحرك كهربائي متميز',
                'description' => 'High-end electric wheelchair with extended battery life and comfort features. Perfect for long hours of worship at Masjid Al-Haram.',
                'description_ar' => 'كرسي متحرك كهربائي فاخر مع عمر بطارية ممتد وميزات راحة. مثالي لساعات العبادة الطويلة في المسجد الحرام.',
                'daily_rate' => 150.00,
                'weekly_rate' => 750.00,
                'monthly_rate' => 2200.00,
                'deposit_amount' => 750.00,
                'battery_range_km' => 30,
                'max_weight_kg' => 120,
                'max_speed_kmh' => 8,
                'features' => json_encode([
                    'Ergonomic joystick',
                    'Memory foam cushion',
                    'Adjustable armrests',
                    'LED front & rear lights',
                    'Bluetooth speaker',
                    'Phone holder',
                    'Cup holder',
                    'Storage basket',
                ]),
                'specifications' => json_encode([
                    'motor' => '350W brushless',
                    'battery' => '24V 20Ah Lithium',
                    'charging_time' => '5-7 hours',
                    'seat_width' => '50cm',
                    'weight' => '30kg',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Heavy Duty Electric Wheelchair',
                'name_ar' => 'كرسي متحرك كهربائي للأوزان الثقيلة',
                'description' => 'Reinforced electric wheelchair designed for larger users. Features enhanced motor power and wider seating.',
                'description_ar' => 'كرسي متحرك كهربائي معزز مصمم للمستخدمين الأكبر حجماً. يتميز بطاقة محرك محسنة ومقعد أوسع.',
                'daily_rate' => 200.00,
                'weekly_rate' => 1000.00,
                'monthly_rate' => 3000.00,
                'deposit_amount' => 1000.00,
                'battery_range_km' => 25,
                'max_weight_kg' => 180,
                'max_speed_kmh' => 6,
                'features' => json_encode([
                    'Reinforced frame',
                    'Extra-wide seat',
                    'Dual battery system',
                    'Heavy-duty tires',
                    'Attendant control option',
                    'Adjustable footrests',
                    'Padded armrests',
                ]),
                'specifications' => json_encode([
                    'motor' => '500W dual motor',
                    'battery' => '24V 30Ah Lithium (dual)',
                    'charging_time' => '6-8 hours',
                    'seat_width' => '60cm',
                    'weight' => '45kg',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'Compact Travel Wheelchair',
                'name_ar' => 'كرسي متحرك كهربائي للسفر',
                'description' => 'Ultra-lightweight and foldable wheelchair perfect for travelers. Fits in car trunks and airplane overhead compartments.',
                'description_ar' => 'كرسي متحرك خفيف الوزن وقابل للطي مثالي للمسافرين. يناسب صناديق السيارات ومقصورات الطائرات.',
                'daily_rate' => 120.00,
                'weekly_rate' => 600.00,
                'monthly_rate' => 1800.00,
                'deposit_amount' => 600.00,
                'battery_range_km' => 15,
                'max_weight_kg' => 100,
                'max_speed_kmh' => 5,
                'features' => json_encode([
                    'One-click folding',
                    'Airline approved',
                    'Lightweight (18kg)',
                    'Removable battery',
                    'Quick-release wheels',
                    'Carry bag included',
                ]),
                'specifications' => json_encode([
                    'motor' => '200W brushless',
                    'battery' => '24V 10Ah Lithium (removable)',
                    'charging_time' => '3-4 hours',
                    'seat_width' => '42cm',
                    'weight' => '18kg',
                    'folded_size' => '60x35x65cm',
                ]),
                'is_active' => true,
            ],
            [
                'name' => 'All-Terrain Electric Wheelchair',
                'name_ar' => 'كرسي متحرك كهربائي لجميع التضاريس',
                'description' => 'Designed for outdoor use with powerful motors and rugged tires. Suitable for Mina and Arafat terrain during Hajj.',
                'description_ar' => 'مصمم للاستخدام الخارجي مع محركات قوية وإطارات متينة. مناسب لتضاريس منى وعرفات خلال موسم الحج.',
                'daily_rate' => 250.00,
                'weekly_rate' => 1250.00,
                'monthly_rate' => 3750.00,
                'deposit_amount' => 1250.00,
                'battery_range_km' => 35,
                'max_weight_kg' => 150,
                'max_speed_kmh' => 10,
                'features' => json_encode([
                    'Off-road tires',
                    'Suspension system',
                    'Weather resistant',
                    'Sun canopy included',
                    'GPS tracking',
                    'Emergency call button',
                    'Extended range battery',
                ]),
                'specifications' => json_encode([
                    'motor' => '600W dual motor',
                    'battery' => '48V 25Ah Lithium',
                    'charging_time' => '6-8 hours',
                    'seat_width' => '55cm',
                    'weight' => '50kg',
                    'ground_clearance' => '10cm',
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            WheelchairType::create($type);
        }
    }
}
