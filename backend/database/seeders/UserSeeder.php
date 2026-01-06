<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Create sample users with various nationalities and verification statuses.
     */
    public function run(): void
    {
        // Default password for all sample users: "password123"
        $defaultPassword = Hash::make('password123');

        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@wheelchair.com'],
            [
                'name' => 'Admin User',
                'phone' => '+966 500 000 000',
                'email' => 'admin@wheelchair.com',
                'password' => Hash::make('password123'),
                'identity_type' => 'national_id',
                'identity_number' => '0000000000',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'verified',
            ]
        );

        // Sample Saudi and international users
        $users = [
            // Saudi Users
            [
                'name' => 'أحمد بن محمد الحربي',
                'phone' => '+966 512 345 678',
                'email' => 'ahmad.alharbi@email.com',
                'password' => $defaultPassword,
                'identity_type' => 'national_id',
                'identity_number' => '1098765432',
                'language' => 'ar',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'فاطمة بنت عبدالله القحطاني',
                'phone' => '+966 501 234 567',
                'email' => 'fatima.alqahtani@email.com',
                'password' => $defaultPassword,
                'identity_type' => 'national_id',
                'identity_number' => '1087654321',
                'language' => 'ar',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            // Pakistani Pilgrims
            [
                'name' => 'Muhammad Khan',
                'phone' => '+92 300 123 4567',
                'email' => 'muhammad.khan@email.pk',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => 'AB1234567',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Aisha Begum',
                'phone' => '+92 321 987 6543',
                'email' => 'aisha.begum@email.pk',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => 'CD7654321',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'pending',
            ],
            // Indonesian Pilgrims
            [
                'name' => 'Budi Santoso',
                'phone' => '+62 812 3456 7890',
                'email' => 'budi.santoso@email.id',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => 'A1234567',
                'language' => 'id',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            [
                'name' => 'Siti Aminah',
                'phone' => '+62 813 9876 5432',
                'email' => 'siti.aminah@email.id',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => 'B7654321',
                'language' => 'id',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            // Egyptian Pilgrims
            [
                'name' => 'محمود حسن',
                'phone' => '+20 100 123 4567',
                'email' => 'mahmoud.hassan@email.eg',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => 'A12345678',
                'language' => 'ar',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            // Malaysian Pilgrims
            [
                'name' => 'Ahmad bin Abdullah',
                'phone' => '+60 12 345 6789',
                'email' => 'ahmad.abdullah@email.my',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => 'A12345678',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            // Indian Pilgrims
            [
                'name' => 'Imran Ali',
                'phone' => '+91 98765 43210',
                'email' => 'imran.ali@email.in',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => 'J1234567',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'pending',
            ],
            // US Pilgrim
            [
                'name' => 'John Smith',
                'phone' => '+1 555 123 4567',
                'email' => 'john.smith@email.com',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => '123456789',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            // UK Pilgrim
            [
                'name' => 'James Wilson',
                'phone' => '+44 7700 900123',
                'email' => 'james.wilson@email.co.uk',
                'password' => $defaultPassword,
                'identity_type' => 'passport',
                'identity_number' => '987654321',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
            // Resident worker (Iqama holder)
            [
                'name' => 'Rajesh Kumar',
                'phone' => '+966 55 123 4567',
                'email' => 'rajesh.kumar@email.com',
                'password' => $defaultPassword,
                'identity_type' => 'iqama',
                'identity_number' => '2345678901',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'verified',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
