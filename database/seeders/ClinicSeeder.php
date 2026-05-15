<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Department;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clinics = [
            [
                'name' => 'Medicare Main Hospital',
                'address' => '123 Medical Center Drive, New York, NY 10001',
                'phone' => '+1 (800) 123-4567',
                'email' => 'main@medicare.com',
                'working_hours' => 'Mon-Fri: 8:00 AM - 8:00 PM, Sat: 9:00 AM - 5:00 PM',
                'status' => 'active',
                'location' => 'New York, NY'
            ],
            [
                'name' => 'Medicare Downtown Clinic',
                'address' => '456 Health Street, New York, NY 10002',
                'phone' => '+1 (800) 234-5678',
                'email' => 'downtown@medicare.com',
                'working_hours' => 'Mon-Fri: 9:00 AM - 6:00 PM',
                'status' => 'active',
                'location' => 'New York, NY'
            ],
            [
                'name' => 'Medicare Westside Medical Center',
                'address' => '789 Wellness Avenue, New York, NY 10003',
                'phone' => '+1 (800) 345-6789',
                'email' => 'westside@medicare.com',
                'working_hours' => 'Mon-Sun: 24/7 Emergency Services',
                'status' => 'active',
                'location' => 'New York, NY'
            ]
        ];
        
        foreach ($clinics as $clinic) {
            Clinic::create($clinic);
        }
    }
}
