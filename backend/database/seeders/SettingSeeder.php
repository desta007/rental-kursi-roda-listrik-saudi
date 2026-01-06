<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Information
            [
                'key' => 'company_name',
                'value' => 'MobilityKSA',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Company Name',
                'description' => 'The name of your company',
            ],
            [
                'key' => 'company_name_ar',
                'value' => 'موبيليتي السعودية',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Company Name (Arabic)',
                'description' => 'Company name in Arabic',
            ],
            [
                'key' => 'company_email',
                'value' => 'info@mobilityksa.com',
                'group' => 'company',
                'type' => 'email',
                'label' => 'Email Address',
                'description' => 'Primary contact email',
            ],
            [
                'key' => 'company_phone',
                'value' => '+966 50 000 0000',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Phone Number',
                'description' => 'Primary contact phone',
            ],
            [
                'key' => 'company_address',
                'value' => 'Riyadh, Saudi Arabia',
                'group' => 'company',
                'type' => 'textarea',
                'label' => 'Address',
                'description' => 'Company address',
            ],

            // Booking Settings
            [
                'key' => 'min_booking_days',
                'value' => '1',
                'group' => 'booking',
                'type' => 'number',
                'label' => 'Minimum Booking Days',
                'description' => 'Minimum number of days for a rental',
            ],
            [
                'key' => 'max_booking_days',
                'value' => '30',
                'group' => 'booking',
                'type' => 'number',
                'label' => 'Maximum Booking Days',
                'description' => 'Maximum number of days for a rental',
            ],
            [
                'key' => 'advance_booking_days',
                'value' => '7',
                'group' => 'booking',
                'type' => 'number',
                'label' => 'Advance Booking Days',
                'description' => 'How many days in advance can customers book',
            ],

            // Payment Settings
            [
                'key' => 'vat_percentage',
                'value' => '15',
                'group' => 'payment',
                'type' => 'number',
                'label' => 'VAT Percentage',
                'description' => 'Value Added Tax percentage',
            ],
            [
                'key' => 'delivery_fee',
                'value' => '50',
                'group' => 'payment',
                'type' => 'number',
                'label' => 'Delivery Fee (SAR)',
                'description' => 'Standard delivery fee',
            ],
            [
                'key' => 'free_delivery_radius_km',
                'value' => '10',
                'group' => 'payment',
                'type' => 'number',
                'label' => 'Free Delivery Radius (km)',
                'description' => 'Distance within which delivery is free',
            ],
            [
                'key' => 'currency',
                'value' => 'SAR',
                'group' => 'payment',
                'type' => 'text',
                'label' => 'Currency',
                'description' => 'Currency code (e.g., SAR, USD)',
            ],

            // Notification Settings
            [
                'key' => 'sms_notifications',
                'value' => '1',
                'group' => 'notifications',
                'type' => 'boolean',
                'label' => 'SMS Notifications',
                'description' => 'Enable SMS notifications for bookings',
            ],
            [
                'key' => 'email_notifications',
                'value' => '1',
                'group' => 'notifications',
                'type' => 'boolean',
                'label' => 'Email Notifications',
                'description' => 'Enable email notifications for bookings',
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@mobilityksa.com',
                'group' => 'notifications',
                'type' => 'email',
                'label' => 'Admin Email',
                'description' => 'Email to receive admin notifications',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
