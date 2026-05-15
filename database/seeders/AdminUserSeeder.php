<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@medicare.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_admin' => true,
                'email_verified_at' => now()
            ]
        );

        $this->command->info('Admin user created successfully.');
    }
}