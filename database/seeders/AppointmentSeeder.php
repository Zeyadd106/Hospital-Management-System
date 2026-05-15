<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Department;
use Illuminate\Support\Facades\Schema;

class AppointmentSeeder extends Seeder
{
    public function run()
    {
        // Seed a department
        $department = Department::firstOrCreate(['name' => 'General'], [
            'name' => 'General',
        ]);

        // Seed a clinic
        $clinic = Clinic::firstOrCreate(['name' => 'Main Clinic'], [
            'name' => 'Main Clinic',
        ]);

        // Seed a user
        $user = User::firstOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed a doctor (include department_id and clinic_id)
        $doctor = Doctor::firstOrCreate(['email' => 'doctor@example.com'], [
            'name' => 'Dr. John Doe',
            'email' => 'doctor@example.com',
            'department_id' => $department->id,
            'clinic_id' => $clinic->id,
        ]);

        // Seed an appointment
        Appointment::create([
            'id' => 1,
        ]);

        // Manually update the appointment with user_id and doctor_id if the columns exist
        $appointment = Appointment::find(1);
        if (Schema::hasColumn('appointments', 'user_id')) {
            $appointment->user_id = $user->id;
        }
        if (Schema::hasColumn('appointments', 'doctor_id')) {
            $appointment->doctor_id = $doctor->id;
        }
        $appointment->save();
    }
}