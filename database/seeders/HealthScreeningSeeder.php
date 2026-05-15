<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthScreeningSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data to prevent duplicates
        DB::table('health_screenings')->truncate();

        // Prepare screening data
        $screenings = [
            [
                'name' => 'Basic Health Check',
                'description' => 'Comprehensive health screening for overall wellness',
                'price' => 99.99,
                'duration' => 60,
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cardiovascular Screening',
                'description' => 'Detailed heart health assessment',
                'price' => 149.99,
                'duration' => 90,
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Women\'s Health Panel',
                'description' => 'Comprehensive screening for women\'s health issues',
                'price' => 199.99,
                'duration' => 120,
                'image' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert screenings
        DB::table('health_screenings')->insert($screenings);
    }
}