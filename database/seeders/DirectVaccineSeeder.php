<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine;
use Illuminate\Support\Facades\DB;

class DirectVaccineSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing vaccines first
        DB::table('vaccines')->truncate();
        
        // Add new vaccines
        $vaccines = [
            [
                'name' => 'COVID-19 Vaccine',
                'description' => 'Protects against COVID-19 infection and reduces severity of symptoms. Recommended for all eligible individuals.',
                'price' => 0.00,
                'recommended_age' => '12 years and older',
                'doses_required' => 2,
                'image' => 'vaccines/covid19.jpg',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Influenza Vaccine',
                'description' => 'Annual vaccine to protect against seasonal flu viruses.',
                'price' => 25.00,
                'recommended_age' => '6 months and older',
                'doses_required' => 1,
                'image' => 'vaccines/influenza.jpg',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'HPV Vaccine',
                'description' => 'Prevents human papillomavirus infection, which can cause cervical cancer and other cancers.',
                'price' => 50.00,
                'recommended_age' => '9-26 years',
                'doses_required' => 2,
                'image' => 'vaccines/hpv.jpg',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert vaccines
        DB::table('vaccines')->insert($vaccines);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
