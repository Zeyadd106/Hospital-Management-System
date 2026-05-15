<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\MessageThread;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageThreadSeeder extends Seeder
{
    public function run()
    {
        // Delete existing data instead of truncating
        Message::query()->delete();
        MessageThread::query()->delete();

        // Get doctor users
        $users = User::where('role', 'doctor')->get();
        
        // Create or find doctors
        $doctors = [];
        foreach ($users as $user) {
            // Find the department
            $department = Department::where('name', $user->department)->first();

            // Find or create doctor, linking the user
            $doctor = Doctor::firstOrCreate(
                ['email' => $user->email],
                [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'department_id' => $department ? $department->id : null,
                    'specialization' => $department ? $department->name : 'General',
                    'is_available' => true,
                    'status' => 'active'
                ]
            );

            $doctors[] = $doctor;
        }

        // Ensure a patient exists
        $patient = User::where('role', 'user')->first();
        if (!$patient) {
            $patient = User::factory()->create([
                'name' => 'Test Patient',
                'email' => 'patient@example.com',
                'role' => 'user',
                'password' => bcrypt('password')
            ]);
        }

        // Create message threads for each doctor
        foreach ($doctors as $doctor) {
            // Create a message thread
            $thread = MessageThread::firstOrCreate(
                [
                    'user_id' => $patient->id,
                    'doctor_id' => $doctor->id
                ],
                [
                    'last_message_at' => now()
                ]
            );

            // Create some sample messages if not exist
            $existingMessages = Message::where('thread_id', $thread->id)->count();
            if ($existingMessages == 0) {
                $messages = [
                    [
                        'thread_id' => $thread->id,
                        'user_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'content' => "Hello Dr. {$doctor->name}, I have a question about my health.",
                        'is_from_user' => true,
                        'created_at' => now()->subHours(2)
                    ],
                    [
                        'thread_id' => $thread->id,
                        'user_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'content' => "Thank you for reaching out. How can I help you today?",
                        'is_from_user' => false,
                        'created_at' => now()->subHours(1)
                    ]
                ];

                Message::insert($messages);
            }
        }

        $this->command->info('Message threads and sample messages created successfully.');
    }
}
