<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $patients = [
            [
                'name' => 'Patient One',
                'email' => 'patient1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'patient'
            ],
            [
                'name' => 'Patient Two',
                'email' => 'patient2@example.com',
                'password' => Hash::make('password123'),
                'role' => 'patient'
            ]
        ];

        foreach ($patients as $patient) {
            User::create($patient);
        }
    }
}