<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'name' => 'Emergency Care',
                'description' => '24/7 emergency medical services with experienced healthcare professionals ready to provide immediate care.',
                'status' => 'active'
            ],
            [
                'name' => 'Heart Care',
                'description' => 'Comprehensive cardiac care services including diagnosis, treatment, and rehabilitation for heart conditions.',
                'status' => 'active'
            ],
            [
                'name' => 'Eye Care',
                'description' => 'Advanced ophthalmology services with state-of-the-art equipment for all your eye care needs.',
                'status' => 'active'
            ],
            [
                'name' => 'Orthopedic Care',
                'description' => 'Specialized treatment for bone and joint conditions with expert orthopedic surgeons.',
                'status' => 'active'
            ],
            [
                'name' => 'Pediatric Care',
                'description' => 'Specialized medical care for infants, children, and adolescents in a child-friendly environment.',
                'status' => 'active'
            ],
            [
                'name' => 'Outpatient Clinics',
                'description' => 'Convenient outpatient services for various medical needs without hospital admission.',
                'status' => 'active'
            ],
            [
                'name' => 'Pharmacy',
                'description' => 'Complete pharmaceutical services with a wide range of medications and professional advice.',
                'status' => 'active'
            ]
        ];

        foreach ($services as $service) {
            Service::create([
                'name' => $service['name'],
                'slug' => Str::slug($service['name']),
                'description' => $service['description'],
                'status' => $service['status']
            ]);
        }
    }
}

