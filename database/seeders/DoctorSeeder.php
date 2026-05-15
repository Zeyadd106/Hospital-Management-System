<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        // Check if doctors already exist
        $existingDoctors = User::where('role', 'doctor')->count();
        if ($existingDoctors > 0) {
            $this->command->info('Doctors already exist. Skipping seeding.');
            return;
        }

        // Create departments
        $departments = [
            [
                'name' => 'General Medicine',
                'description' => 'General medical consultation and treatment'
            ],
            [
                'name' => 'Cardiology',
                'description' => 'Heart and cardiovascular health'
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Child and adolescent health care'
            ],
            [
                'name' => 'Neurology',
                'description' => 'Brain and nervous system health'
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Musculoskeletal system health'
            ]
        ];

        $createdDepartments = [];
        foreach ($departments as $dept) {
            $createdDepartments[] = Department::firstOrCreate(
                ['name' => $dept['name']],
                $dept
            );
        }

        // Create doctors
        $doctorsData = [
            [
                'name' => 'Dr. John Smith',
                'email' => 'john.smith@medicare.com',
                'department' => 'General Medicine',
                'gender' => 'male',
                'phone' => '1234567890',
                'address' => '123 Medical Street',
                'date_of_birth' => '1980-01-01'
            ],
            [
                'name' => 'Dr. Emily Johnson',
                'email' => 'emily.johnson@medicare.com',
                'department' => 'Cardiology',
                'gender' => 'female',
                'phone' => '2345678901',
                'address' => '456 Heart Health Avenue',
                'date_of_birth' => '1975-05-15'
            ],
            [
                'name' => 'Dr. Michael Lee',
                'email' => 'michael.lee@medicare.com',
                'department' => 'Pediatrics',
                'gender' => 'male',
                'phone' => '3456789012',
                'address' => '789 Children\'s Lane',
                'date_of_birth' => '1985-09-20'
            ],
            [
                'name' => 'Dr. Sarah Rodriguez',
                'email' => 'sarah.rodriguez@medicare.com',
                'department' => 'Neurology',
                'gender' => 'female',
                'phone' => '4567890123',
                'address' => '234 Brain Health Road',
                'date_of_birth' => '1978-03-10'
            ],
            [
                'name' => 'Dr. David Kim',
                'email' => 'david.kim@medicare.com',
                'department' => 'Orthopedics',
                'gender' => 'male',
                'phone' => '5678901234',
                'address' => '567 Bone & Joint Street',
                'date_of_birth' => '1982-11-25'
            ]
        ];

        foreach ($doctorsData as $doctorData) {
            $department = Department::where('name', $doctorData['department'])->first();
            
            User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'department_id' => $department->id,
                'email_verified_at' => now(),
                'phone' => $doctorData['phone'],
                'address' => $doctorData['address'],
                'date_of_birth' => $doctorData['date_of_birth'],
                'gender' => $doctorData['gender']
            ]);
        }

        $this->command->info('Doctors and departments created successfully.');
    }
}