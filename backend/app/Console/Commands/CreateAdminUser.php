<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user 
                            {--email=admin@wheelchair.com : Admin email address}
                            {--password=password123 : Admin password}
                            {--name=Admin User : Admin name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update admin user for admin panel login';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'name' => $name,
                'password' => Hash::make($password),
                'status' => 'active',
                'verification_status' => 'verified',
            ]);
            $this->info("✅ Admin user updated successfully!");
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'phone' => '+966 500 000 000',
                'identity_type' => 'national_id',
                'identity_number' => '0000000000',
                'language' => 'en',
                'status' => 'active',
                'verification_status' => 'verified',
            ]);
            $this->info("✅ Admin user created successfully!");
        }

        $this->info("📧 Email: {$email}");
        $this->info("🔑 Password: {$password}");
        $this->info("\nYou can now login at: http://127.0.0.1:8000/admin/login");

        return Command::SUCCESS;
    }
}
