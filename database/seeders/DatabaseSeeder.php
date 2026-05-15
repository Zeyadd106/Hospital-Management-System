<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Log::info('Starting database seeding');
        
        // Seed basic users
        $this->call([
            AdminUserSeeder::class,
            DoctorSeeder::class,
            HealthScreeningSeeder::class,
        ]);

        // Seed departments
        $this->call(DepartmentSeeder::class);

        // Seed other data
        $this->call([
            DirectVaccineSeeder::class,
            PharmacySeeder::class,
            ClinicSeeder::class,
            MessageThreadSeeder::class,
            MedicationSeeder::class,
            // Add other seeders as needed
        ]);
        
        Log::info('Database seeding completed');
    }
}

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // No need to seed services here as it's already seeded in DatabaseSeeder
    }
}
