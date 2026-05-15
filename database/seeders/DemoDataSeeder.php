<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Pharmacy;
use App\Models\Medication;
use App\Models\PrescriptionRefill;
use Illuminate\Support\Str;
use Faker\Factory;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@medicare.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => true
        ]);

        // Create Pharmacy Admin User
        $pharmacyAdmin = User::create([
            'name' => 'Pharmacy Admin',
            'email' => 'pharmacyadmin@medicare.com',
            'password' => bcrypt('pharma123'),
            'role' => 'pharmacy_admin',
            'status' => true
        ]);

        // Create Doctor Users
        $doctors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'dr.sarah@medicare.com',
                'password' => bcrypt('doctor123'),
                'role' => 'doctor',
                'status' => 'active'
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'dr.michael@medicare.com',
                'password' => bcrypt('doctor123'),
                'role' => 'doctor',
                'status' => 'active'
            ],
            [
                'name' => 'Dr. Emily Wilson',
                'email' => 'dr.emily@medicare.com',
                'password' => bcrypt('doctor123'),
                'role' => 'doctor',
                'status' => 'active'
            ]
        ];

        foreach ($doctors as $doctorData) {
            $user = User::create($doctorData);
            
            // Create doctor profile
            $user->doctorProfile()->create([
                'specialization' => fake()->randomElement(['Cardiology', 'Neurology', 'Pediatrics', 'Dermatology', 'Internal Medicine']),
                'qualification' => fake()->randomElement(['MBBS', 'MBBS, MD', 'MBBS, MD, DM']),
                'experience_years' => rand(5, 20),
                'consultation_fee' => rand(100, 500),
                'about' => fake()->paragraph,
                'rating' => round(rand(35, 50) / 10, 1),
                'availability' => [
                    'monday' => ['9:00', '17:00'],
                    'tuesday' => ['9:00', '17:00'],
                    'wednesday' => ['9:00', '17:00'],
                    'thursday' => ['9:00', '17:00'],
                    'friday' => ['9:00', '17:00'],
                    'saturday' => ['10:00', '14:00'],
                    'sunday' => ['10:00', '14:00']
                ],
                'is_available' => true
            ]);
        }

        // Create Demo Pharmacy
        try {
            $pharmacy = Pharmacy::create([
                'name' => 'MediCare Pharmacy',
                'address' => '123 Medical Street, Healthcare City',
                'phone' => '(555) 123-4567',
                'email' => 'pharmacy@medicare.com',
                'license_number' => 'PHR-123456',
                'status' => true
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating pharmacy', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return;
        }

        // Create Demo Medications
        $medications = [
            [
                'name' => 'Aspirin 81mg',
                'manufacturer' => 'Bayer',
                'strength' => '81mg',
                'form' => 'Tablet',
                'quantity' => 100,
                'price' => 5.99,
                'expiry_date' => '2026-12-31',
                'batch_number' => 'ASP20261231'
            ],
            [
                'name' => 'Ibuprofen 200mg',
                'manufacturer' => 'Pfizer',
                'strength' => '200mg',
                'form' => 'Tablet',
                'quantity' => 150,
                'price' => 3.99,
                'expiry_date' => '2026-11-30',
                'batch_number' => 'IBU20261130'
            ],
            [
                'name' => 'Acetaminophen 500mg',
                'manufacturer' => 'Johnson & Johnson',
                'strength' => '500mg',
                'form' => 'Tablet',
                'quantity' => 200,
                'price' => 4.99,
                'expiry_date' => '2026-10-31',
                'batch_number' => 'ACE20261031'
            ]
        ];

        foreach ($medications as $medication) {
            try {
                Medication::create([
                    'name' => $medication['name'],
                    'manufacturer' => $medication['manufacturer'],
                    'strength' => $medication['strength'],
                    'form' => $medication['form'],
                    'quantity' => $medication['quantity'],
                    'price' => $medication['price'],
                    'expiry_date' => $medication['expiry_date'],
                    'batch_number' => $medication['batch_number'],
                    'pharmacy_id' => $pharmacy->id,
                    'status' => true
                ]);
            } catch (\Exception $e) {
                \Log::error('Error creating medication', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Create Demo Prescription Refill
        try {
            PrescriptionRefill::create([
                'prescription_id' => 1,
                'pharmacy_id' => $pharmacy->id,
                'status' => 'pending',
                'notes' => 'Patient requested refill for 30 days supply'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating prescription refill', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Print login credentials
        \Log::info('Demo User Credentials:', [
            'Admin' => 'admin@medicare.com / admin123',
            'Pharmacy Admin' => 'pharmacyadmin@medicare.com / pharma123',
            'Doctors' => [
                'dr.sarah@medicare.com / doctor123',
                'dr.michael@medicare.com / doctor123',
                'dr.emily@medicare.com / doctor123'
            ]
        ]);
    }
}
