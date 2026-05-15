<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            [
                'name' => 'Cardiology',
                'description' => 'Department specializing in heart and cardiovascular conditions.',
                'status' => 'active'
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Department focused on medical care for infants, children, and adolescents.',
                'status' => 'active'
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Department dealing with musculoskeletal system conditions.',
                'status' => 'active'
            ],
            [
                'name' => 'Neurology',
                'description' => 'Department specializing in disorders of the nervous system.',
                'status' => 'active'
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Department focused on skin conditions and treatments.',
                'status' => 'active'
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Department specializing in eye care and vision health.',
                'status' => 'active'
            ]
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}

