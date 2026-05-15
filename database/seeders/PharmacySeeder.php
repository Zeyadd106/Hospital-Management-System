<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pharmacy;

class PharmacySeeder extends Seeder
{
    public function run()
    {
        // First check if pharmacy already exists
        $pharmacy = Pharmacy::firstOrCreate([
            'name' => 'MediCare Pharmacy',
            'address' => '123 Medical Street, Health City',
            'phone' => '+1234567890',
            'license_number' => 'PHR-2025-0001',
            'status' => true
        ], [
            'email' => 'contact@medicarepharmacy.com'
        ]);

        // Create pharmacy staff accounts
        $pharmacyStaff = User::updateOrCreate([
            'email' => 'pharmacy@admin.com'
        ], [
            'name' => 'Pharmacy Admin',
            'password' => Hash::make('admin123'),
            'phone' => '+1234567890',
            'address' => '123 Medical Street, Health City',
            'role' => 'pharmacy_admin',
            'is_admin' => true,
            'pharmacy_id' => $pharmacy->id
        ]);

        // Create additional pharmacy staff
        $pharmacyStaff2 = User::updateOrCreate([
            'email' => 'pharmacy@staff.com'
        ], [
            'name' => 'Pharmacy Staff',
            'password' => Hash::make('staff123'),
            'phone' => '+1234567891',
            'address' => '123 Medical Street, Health City',
            'role' => 'pharmacy_staff',
            'is_admin' => false,
            'pharmacy_id' => $pharmacy->id
        ]);

        $this->command->info('Pharmacy staff accounts have been created successfully!');
    }
}
