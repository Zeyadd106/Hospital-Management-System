<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vaccine;

class SeedVaccines extends Command
{
    protected $signature = 'seed:vaccines';
    protected $description = 'Seed the vaccines table with sample data';

    public function handle()
    {
        $this->info('Seeding vaccines...');
        
        $vaccines = [
            [
                'name' => 'COVID-19 Vaccine',
                'description' => 'Protects against COVID-19 infection and reduces severity of symptoms. Recommended for all eligible individuals.',
                'manufacturer' => 'Pfizer-BioNTech',
                'recommended_age' => '12 years and older',
                'doses_required' => 2,
                'price' => 0.00,
                'is_available' => true
            ],
            [
                'name' => 'Flu Shot',
                'description' => 'Annual vaccine that protects against seasonal influenza viruses. Important for preventing flu complications.',
                'manufacturer' => 'Various',
                'recommended_age' => '6 months and older',
                'doses_required' => 1,
                'price' => 25.00,
                'is_available' => true
            ],
            [
                'name' => 'MMR Vaccine',
                'description' => 'Protects against Measles, Mumps, and Rubella. Essential for children and those at risk.',
                'manufacturer' => 'Merck',
                'recommended_age' => '12 months and older',
                'doses_required' => 2,
                'price' => 50.00,
                'is_available' => true
            ],
            [
                'name' => 'Tdap Vaccine',
                'description' => 'Protects against Tetanus, Diphtheria, and Pertussis. Recommended for adolescents and adults.',
                'manufacturer' => 'GSK',
                'recommended_age' => '11 years and older',
                'doses_required' => 1,
                'price' => 45.00,
                'is_available' => true
            ],
            [
                'name' => 'Hepatitis B Vaccine',
                'description' => 'Prevents Hepatitis B virus infection. Important for newborns and those at risk.',
                'manufacturer' => 'Merck',
                'recommended_age' => 'All ages',
                'doses_required' => 3,
                'price' => 60.00,
                'is_available' => true
            ]
        ];

        foreach ($vaccines as $vaccine) {
            Vaccine::updateOrCreate(
                ['name' => $vaccine['name']],
                $vaccine
            );
        }

        $this->info('Vaccines seeded successfully!');
    }
}

