<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vaccines = [
            [
                'name' => 'COVID-19 Vaccine',
                'description' => 'Protects against the SARS-CoV-2 virus that causes COVID-19.',
                'recommended_age' => '12 years and older',
                'price' => 0.00,
                'is_available' => true,
            ],
            [
                'name' => 'Influenza (Flu) Vaccine',
                'description' => 'Annual vaccine that protects against the most common strains of influenza viruses.',
                'recommended_age' => '6 months and older',
                'price' => 25.00,
                'is_available' => true,
            ],
            [
                'name' => 'Tetanus, Diphtheria, and Pertussis (Tdap) Vaccine',
                'description' => 'Protects against tetanus, diphtheria, and pertussis (whooping cough).',
                'recommended_age' => '11 years and older',
                'price' => 45.00,
                'is_available' => true,
            ],
            [
                'name' => 'Measles, Mumps, and Rubella (MMR) Vaccine',
                'description' => 'Protects against measles, mumps, and rubella.',
                'recommended_age' => '12 months and older',
                'price' => 50.00,
                'is_available' => true,
            ],
            [
                'name' => 'Hepatitis B Vaccine',
                'description' => 'Protects against hepatitis B virus infection.',
                'recommended_age' => 'All ages',
                'price' => 60.00,
                'is_available' => true,
            ],
            [
                'name' => 'Human Papillomavirus (HPV) Vaccine',
                'description' => 'Protects against HPV types that cause most cervical cancers and other HPV-related diseases.',
                'recommended_age' => '11-26 years',
                'price' => 120.00,
                'is_available' => true,
            ],
            [
                'name' => 'Pneumococcal Vaccine',
                'description' => 'Protects against pneumococcal disease, which can cause pneumonia, meningitis, and bloodstream infections.',
                'recommended_age' => '65 years and older',
                'price' => 85.00,
                'is_available' => true,
            ],
        ];

        foreach ($vaccines as $vaccine) {
            Vaccine::create($vaccine);
        }
    }
}