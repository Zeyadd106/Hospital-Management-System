<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Message;
use App\Models\HealthScreeningBooking;
use App\Models\VaccinationBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:doctor']);
    }

    public function index()
    {
        try {
            // Updated query to use both status and is_available
            $doctors = Doctor::where('status', 'active')
                      ->where('is_available', true)
                      ->get();
            
            // Get unique departments for filters
            $departments = Doctor::distinct('department')
                ->pluck('department')
                ->toArray();
                
            // Get unique specialties for filters
            $specialties = Doctor::distinct('specialty')
                ->pluck('specialty')
                ->toArray();
                
            return view('doctors.index', compact('doctors', 'departments', 'specialties'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctors: ' . $e->getMessage());
            return view('doctors.index', [
                'doctors' => collect([]),
                'departments' => [],
                'specialties' => []
            ]);
        }
    }

    public function show($id)
    {
        try {
            // Updated to check both status and is_available
            $doctor = Doctor::where('id', $id)
                     ->where('status', 'active')
                     ->firstOrFail();
                     
            return view('doctors.show', compact('doctor'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor details: ' . $e->getMessage());
            return redirect()->route('doctors.index')
                           ->with('error', 'Doctor not found.');
        }
    }

    public function patients()
    {
        $user = Auth::user();
        
        if (!$user->isDoctor()) {
            abort(403, 'Unauthorized action');
        }
        
        $query = Patient::query();
        
        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($status = request('status')) {
            $query->where('status', $status);
        }
        
        $patients = $query->with(['appointments' => function($q) {
            $q->where('doctor_id', Auth::id())
              ->whereDate('date', '>=', now())->get();
        }])->get();
        
        return response()->json($patients);
    }

    public function getUnreadMessages()
    {
        $user = Auth::user();
        
        if (!$user->isDoctor()) {
            abort(403, 'Unauthorized action');
        }
        
        $unreadMessages = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return response()->json(['unread_messages' => $unreadMessages]);
    }

    public function profile()
    {
        try {
            $doctor = Auth::user();
            return view('doctor.profile.index', compact('doctor'));
        } catch (\Exception $e) {
            Log::error('Error loading doctor profile: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load profile');
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $doctor = Auth::user();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $doctor->id,
                'phone' => 'nullable|string|max:20',
                'specialization' => 'nullable|string|max:255',
            ]);

            $doctor->update($request->only([
                'name', 'email', 'phone', 'specialization'
            ]));

            return redirect()->route('doctor.profile')
                ->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating doctor profile: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile');
        }
    }

    public function schedule()
    {
        try {
            $doctor = Auth::user();
            $schedules = $doctor->schedules()->get();
            return view('doctor.schedule.index', compact('doctor', 'schedules'));
        } catch (\Exception $e) {
            Log::error('Error loading doctor schedule: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load schedule');
        }
    }

    public function screenings()
    {
        try {
            $doctor = Auth::user();
            
            // Fetch health screenings assigned to this doctor
            $screenings = HealthScreeningBooking::whereHas('healthScreening', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with(['patient', 'healthScreening'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
            return view('doctor.screenings.index', compact('screenings'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor screenings: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load health screenings');
        }
    }

    public function vaccinations()
    {
        try {
            $doctor = Auth::user();
            
            // Fetch vaccination bookings assigned to this doctor's clinic
            $vaccinations = VaccinationBooking::whereHas('clinic', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with(['patient', 'vaccine', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
            return view('doctor.vaccinations.index', compact('vaccinations'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor vaccinations: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load vaccinations');
        }
    }

    public function messages()
    {
        try {
            $doctor = Auth::user();
            
            // Fetch dashboard stats
            $stats = $this->getDashboardStats($doctor);
            
            // Get unique patients who have messaged this doctor
            $conversations = User::whereHas('messages', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with(['messages' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                    ->orderBy('created_at', 'desc');
            }])
            ->withCount(['messages' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                    ->where('read_at', null);
            }])
            ->paginate(10);
            
            return view('doctor.messages', compact('conversations', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor conversations: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load conversations');
        }
    }

    private function getDashboardStats($doctor)
    {
        return [
            'todayAppointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->count(),
            'activePatients' => Patient::whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })->count(),
            'pendingReports' => Report::where('doctor_id', $doctor->id)
                ->where('status', 'pending')
                ->count(),
            'newMessages' => Message::where('doctor_id', $doctor->id)
                ->whereNull('read_at')
                ->count()
        ];
    }

    public function messageThread($userId)
    {
        try {
            $doctor = Auth::user();
            
            // Fetch messages between doctor and specific patient
            $messages = Message::where(function($query) use ($doctor, $userId) {
                $query->where('doctor_id', $doctor->id)
                      ->where('user_id', $userId);
            })
            ->orWhere(function($query) use ($doctor, $userId) {
                $query->where('user_id', $doctor->id)
                      ->where('doctor_id', $userId);
            })
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get();
            
            // Mark all messages from this user as read
            Message::where('doctor_id', $doctor->id)
                ->where('user_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            
            $patient = User::findOrFail($userId);
            
            return view('doctor.messages.thread', compact('messages', 'patient'));
        } catch (\Exception $e) {
            Log::error('Error fetching message thread: ' . $e->getMessage());
            return redirect()->route('doctor.messages.index')
                ->with('error', 'Unable to load message thread');
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'content' => 'required|string|max:1000'
            ]);
            
            $doctor = Auth::user();
            
            $message = Message::create([
                'user_id' => $request->user_id,
                'doctor_id' => $doctor->id,
                'content' => $request->content,
                'is_from_user' => false,
                'read_at' => null
            ]);
            
            return redirect()->route('doctor.messages.thread', $request->user_id)
                ->with('success', 'Message sent successfully');
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to send message');
        }
    }

    public function appointments()
    {
        try {
            $doctor = Auth::user();
            
            $appointments = Appointment::where('doctor_id', $doctor->id)
                ->with(['patient'])
                ->orderBy('appointment_date', 'desc')
                ->paginate(10);
            
            return view('doctor.appointments.index', compact('appointments'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor appointments: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load appointments');
        }
    }

    public function patientsList()
    {
        try {
            $doctor = Auth::user();
            
            $patients = Patient::whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->withCount(['appointments' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            }])
            ->paginate(10);
            
            return view('doctor.patients.index', compact('patients'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor patients: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load patients');
        }
    }

    public function reports()
    {
        try {
            $doctor = Auth::user();
            
            $reports = Report::where('doctor_id', $doctor->id)
                ->with(['patient'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('doctor.reports.index', compact('reports'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor reports: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load reports');
        }
    }

    public function prescriptions()
    {
        try {
            $doctor = Auth::user();
            
            $prescriptions = Prescription::where('doctor_id', $doctor->id)
                ->with(['patient', 'medications'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('doctor.prescriptions.index', compact('prescriptions'));
        } catch (\Exception $e) {
            Log::error('Error fetching doctor prescriptions: ' . $e->getMessage());
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Unable to load prescriptions');
        }
    }

    public function availability()
    {
        $doctor = Auth::user()->doctorProfile;
        $schedules = $doctor->schedules ?? collect();
        return view('doctor.availability', compact('doctor', 'schedules'));
    }

    public function updateAvailability(Request $request)
    {
        $request->validate([
            'days' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $doctor = Auth::user()->doctorProfile;

        // Update or create doctor schedules
        $doctor->schedules()->delete(); // Remove existing schedules
        
        foreach ($request->days as $day) {
            $doctor->schedules()->create([
                'day' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);
        }

        return redirect()->route('doctor.availability.index')
            ->with('success', 'Availability updated successfully');
    }
}
