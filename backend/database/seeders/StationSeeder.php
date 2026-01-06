<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Real pickup stations in Mecca, Saudi Arabia.
     */
    public function run(): void
    {
        $stations = [
            // Masjid Al-Haram Gates (Mecca)
            [
                'name' => 'Masjid Al-Haram - King Abdulaziz Gate',
                'name_ar' => 'المسجد الحرام - باب الملك عبدالعزيز',
                'city' => 'Mecca',
                'address' => 'King Abdulaziz Gate (Gate 1), Masjid Al-Haram, Mecca, Saudi Arabia',
                'address_ar' => 'باب الملك عبدالعزيز (باب 1)، المسجد الحرام، مكة المكرمة',
                'latitude' => 21.4225,
                'longitude' => 39.8262,
                'operating_hours' => '24/7',
                'contact_phone' => '+966 12 545 5555',
                'is_active' => true,
            ],
            [
                'name' => 'Masjid Al-Haram - King Fahd Gate',
                'name_ar' => 'المسجد الحرام - باب الملك فهد',
                'city' => 'Mecca',
                'address' => 'King Fahd Gate (Gate 79), Masjid Al-Haram, Mecca, Saudi Arabia',
                'address_ar' => 'باب الملك فهد (باب 79)، المسجد الحرام، مكة المكرمة',
                'latitude' => 21.4228,
                'longitude' => 39.8265,
                'operating_hours' => '24/7',
                'contact_phone' => '+966 12 545 5556',
                'is_active' => true,
            ],
            [
                'name' => 'Masjid Al-Haram - Umrah Gate',
                'name_ar' => 'المسجد الحرام - باب العمرة',
                'city' => 'Mecca',
                'address' => 'Umrah Gate (Gate 92), Masjid Al-Haram, Mecca, Saudi Arabia',
                'address_ar' => 'باب العمرة (باب 92)، المسجد الحرام، مكة المكرمة',
                'latitude' => 21.4232,
                'longitude' => 39.8268,
                'operating_hours' => '24/7',
                'contact_phone' => '+966 12 545 5557',
                'is_active' => true,
            ],
            // Hotels in Mecca
            [
                'name' => 'Hilton Makkah Convention Hotel',
                'name_ar' => 'فندق هيلتون مكة للمؤتمرات',
                'city' => 'Mecca',
                'address' => 'Jabal Omar, Hilton Makkah Convention Hotel, Mecca, Saudi Arabia',
                'address_ar' => 'جبل عمر، فندق هيلتون مكة للمؤتمرات، مكة المكرمة',
                'latitude' => 21.4178,
                'longitude' => 39.8243,
                'operating_hours' => '06:00 - 23:00',
                'contact_phone' => '+966 12 531 0000',
                'is_active' => true,
            ],
            [
                'name' => 'Swissôtel Makkah',
                'name_ar' => 'سويس أوتيل مكة',
                'city' => 'Mecca',
                'address' => 'Abraj Al-Bait Complex, Swissôtel Makkah, Mecca, Saudi Arabia',
                'address_ar' => 'مجمع أبراج البيت، سويس أوتيل مكة، مكة المكرمة',
                'latitude' => 21.4189,
                'longitude' => 39.8256,
                'operating_hours' => '06:00 - 23:00',
                'contact_phone' => '+966 12 571 7777',
                'is_active' => true,
            ],
            [
                'name' => 'Makkah Clock Royal Tower - Fairmont',
                'name_ar' => 'برج الساعة الملكي - فيرمونت',
                'city' => 'Mecca',
                'address' => 'Abraj Al-Bait Complex, Clock Tower, Mecca, Saudi Arabia',
                'address_ar' => 'مجمع أبراج البيت، برج الساعة، مكة المكرمة',
                'latitude' => 21.4194,
                'longitude' => 39.8265,
                'operating_hours' => '06:00 - 23:00',
                'contact_phone' => '+966 12 571 8888',
                'is_active' => true,
            ],
            // Mina & Arafat (for Hajj)
            [
                'name' => 'Mina Camp Area',
                'name_ar' => 'منطقة مخيمات منى',
                'city' => 'Mecca',
                'address' => 'Mina Valley, Makkah Province, Saudi Arabia',
                'address_ar' => 'وادي منى، منطقة مكة المكرمة',
                'latitude' => 21.4139,
                'longitude' => 39.8933,
                'operating_hours' => 'During Hajj Season',
                'contact_phone' => '+966 12 545 6000',
                'is_active' => true,
            ],
            [
                'name' => 'Arafat Pilgrim Station',
                'name_ar' => 'محطة حجاج عرفات',
                'city' => 'Mecca',
                'address' => 'Plain of Arafat, Makkah Province, Saudi Arabia',
                'address_ar' => 'صعيد عرفات، منطقة مكة المكرمة',
                'latitude' => 21.3549,
                'longitude' => 39.9842,
                'operating_hours' => 'During Hajj Season',
                'contact_phone' => '+966 12 545 6001',
                'is_active' => true,
            ],
            // Hospitals
            [
                'name' => 'King Abdullah Medical City',
                'name_ar' => 'مدينة الملك عبدالله الطبية',
                'city' => 'Mecca',
                'address' => 'Al Mashair Al Mugaddassah, Mecca, Saudi Arabia',
                'address_ar' => 'المشاعر المقدسة، مكة المكرمة',
                'latitude' => 21.3928,
                'longitude' => 39.8744,
                'operating_hours' => '24/7',
                'contact_phone' => '+966 12 556 5000',
                'is_active' => true,
            ],
            [
                'name' => 'Al Noor Specialist Hospital',
                'name_ar' => 'مستشفى النور التخصصي',
                'city' => 'Mecca',
                'address' => 'Al Aziziyah District, Mecca, Saudi Arabia',
                'address_ar' => 'حي العزيزية، مكة المكرمة',
                'latitude' => 21.4089,
                'longitude' => 39.8436,
                'operating_hours' => '24/7',
                'contact_phone' => '+966 12 566 4444',
                'is_active' => true,
            ],
        ];

        foreach ($stations as $station) {
            Station::create($station);
        }
    }
}
