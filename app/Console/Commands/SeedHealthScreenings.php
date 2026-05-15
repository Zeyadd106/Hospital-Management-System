<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HealthScreening;

class SeedHealthScreenings extends Command
{
    protected $signature = 'seed:health-screenings';
    protected $description = 'Seed the health screenings table with sample data';

    public function handle()
    {
        $this->info('Seeding health screenings...');
        
        $healthScreenings = [
            [
                'name' => 'Blood Pressure Screening',
                'description' => 'Monitor your blood pressure and detect early signs of hypertension.',
                'price' => 50.00,
                'duration' => 30,
                'category' => 'basic',
                'recommended_age_group' => 'adults',
                'icon' => 'fas fa-heartbeat',
                'is_available' => true
            ],
            [
                'name' => 'Cholesterol Testing',
                'description' => 'Track your cholesterol levels for better heart health.',
                'price' => 75.00,
                'duration' => 45,
                'category' => 'basic',
                'recommended_age_group' => 'adults',
                'icon' => 'fas fa-vial',
                'is_available' => true
            ],
            [
                'name' => 'Vision & Hearing Tests',
                'description' => 'Ensure your vision and hearing are in top condition.',
                'price' => 100.00,
                'duration' => 60,
                'category' => 'basic',
                'recommended_age_group' => 'all',
                'icon' => 'fas fa-eye',
                'is_available' => true
            ],
            [
                'name' => 'Diabetes Screening',
                'description' => 'Get tested for diabetes and manage your health effectively.',
                'price' => 80.00,
                'duration' => 45,
                'category' => 'advanced',
                'recommended_age_group' => 'adults',
                'icon' => 'fas fa-burn',
                'is_available' => true
            ],
            [
                'name' => 'Lung Function Test',
                'description' => 'Check for potential respiratory issues with a lung function test.',
                'price' => 90.00,
                'duration' => 45,
                'category' => 'advanced',
                'recommended_age_group' => 'adults',
                'icon' => 'fas fa-lungs',
                'is_available' => true
            ],
            [
                'name' => 'Cancer Screening',
                'description' => 'Early detection through comprehensive cancer screening.',
                'price' => 200.00,
                'duration' => 90,
                'category' => 'comprehensive',
                'recommended_age_group' => 'adults',
                'icon' => 'fas fa-flask',
                'is_available' => true
            ]
        ];

        foreach ($healthScreenings as $screening) {
            HealthScreening::updateOrCreate(
                ['name' => $screening['name']],
                $screening
            );
        }

        $this->info('Health screenings seeded successfully!');
    }
}

