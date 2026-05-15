<?php

namespace App\Http\Controllers;

use App\Models\VaccinationBooking;
use App\Models\HealthScreeningBooking;
use App\Models\Appointment;
use App\Models\PrescriptionRequest;
use App\Models\Message;
use App\Models\Prescription;
use App\Models\DoctorSchedule;
use App\Models\HealthScreening;
use App\Models\ClinicPatient;
use App\Models\PrescriptionRefill;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Notifications\AppointmentConfirmedNotification;
use App\Notifications\AppointmentRejectedNotification;
use App\Notifications\HealthScreeningApprovedNotification;
use App\Notifications\HealthScreeningRejectedNotification;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctor = Auth::user();
        
        // Quick stats for dashboard cards
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->count();
            
        $pendingScreenings = HealthScreeningBooking::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();
            
        $pendingVaccinations = VaccinationBooking::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();
            
        $unreadMessages = Message::where('doctor_id', $doctor->id)
            ->whereNull('read_at')
            ->count();
        
        // Recent patients
        $recentPatients = User::whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with(['appointments' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->latest();
            }])
            ->take(5)
            ->get()
            ->map(function($patient) {
                $patient->last_visit = $patient->appointments->first()?->appointment_date;
                $patient->status = $patient->appointments->first()?->status == 'completed' ? 'active' : 'pending';
                return $patient;
            });
        
        // Upcoming appointments
        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', '!=', 'completed')
            ->where('appointment_date', '>=', now())
            ->with('user')
            ->orderBy('appointment_date')
            ->take(5)
            ->get();
        
        // Recent health screenings
        $recentScreenings = HealthScreeningBooking::where('doctor_id', $doctor->id)
            ->with(['user', 'test'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('doctor.dashboard.index', compact(
            'todayAppointments',
            'pendingScreenings',
            'pendingVaccinations',
            'unreadMessages',
            'recentPatients',
            'upcomingAppointments',
            'recentScreenings'
        ));
    }

    public function appointments()
    {
        // Get the currently logged-in doctor's ID
        $doctorId = Auth::id();

        // Fetch all appointments for this doctor, including past, current, and future
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->with(['patient', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function todayAppointments()
    {
        $doctorId = Auth::id();
        $today = now()->toDateString();

        $appointments = Appointment::whereHas('doctor', function($query) use ($doctorId) {
            $query->where('id', $doctorId);
        })
        ->whereDate('appointment_date', $today)
        ->with(['patient', 'clinic', 'doctor'])
        ->orderBy('appointment_time', 'asc')
        ->paginate(15);
        
        return view('doctor.appointments.today', compact('appointments'));
    }

    public function upcomingAppointments()
    {
        $doctorId = Auth::id();

        $appointments = Appointment::whereHas('doctor', function($query) use ($doctorId) {
            $query->where('id', $doctorId);
        })
        ->where('appointment_date', '>', now()->toDateString())
        ->with(['patient', 'clinic', 'doctor'])
        ->orderBy('appointment_date', 'asc')
        ->paginate(15);
        
        return view('doctor.appointments.upcoming', compact('appointments'));
    }

    public function pastAppointments()
    {
        $doctorId = Auth::id();

        $appointments = Appointment::whereHas('doctor', function($query) use ($doctorId) {
            $query->where('id', $doctorId);
        })
        ->where('appointment_date', '<', now()->toDateString())
        ->with(['patient', 'clinic', 'doctor'])
        ->orderBy('appointment_date', 'desc')
        ->paginate(15);
        
        return view('doctor.appointments.past', compact('appointments'));
    }

    public function acceptAppointment($id)
    {
        $appointment = Appointment::where('doctor_id', Auth::id())
            ->with('user')
            ->findOrFail($id);
        
        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Optional: Send notification to patient
        if ($appointment->user) {
            $appointment->user->notify(new AppointmentConfirmedNotification($appointment));
        }

        return redirect()->route('doctor.appointments.index')
            ->with('success', 'Appointment confirmed successfully');
    }

    public function rejectAppointment(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $appointment = Appointment::where('doctor_id', Auth::id())
            ->findOrFail($id);
        
        $appointment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
            'rejected_at' => now()
        ]);

        // Optional: Send notification to patient
        $appointment->user->notify(new AppointmentRejectedNotification($appointment));

        return redirect()->route('doctor.appointments.index')
            ->with('warning', 'Appointment rejected');
    }

    public function showAppointment($id)
    {
        $appointment = Appointment::where('doctor_id', Auth::id())
            ->with(['patient', 'clinic', 'prescriptions', 'healthScreenings'])
            ->findOrFail($id);
        
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function approveHealthScreening($id)
    {
        $screening = HealthScreeningBooking::findOrFail($id);
        
        if ($screening->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $screening->status = 'approved';
        $screening->save();

        return redirect()->back()->with('success', 'Health screening approved successfully.');
    }

    public function rejectHealthScreening($id)
    {
        $screening = HealthScreeningBooking::findOrFail($id);
        
        if ($screening->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $screening->status = 'rejected';
        $screening->save();

        return redirect()->back()->with('success', 'Health screening rejected successfully.');
    }

    public function approveVaccination($id)
    {
        $vaccination = VaccinationBooking::findOrFail($id);
        
        if ($vaccination->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $vaccination->status = 'approved';
        $vaccination->save();

        return redirect()->back()->with('success', 'Vaccination approved successfully.');
    }

    public function rejectVaccination($id)
    {
        $vaccination = VaccinationBooking::findOrFail($id);
        
        if ($vaccination->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $vaccination->status = 'rejected';
        $vaccination->save();

        return redirect()->back()->with('success', 'Vaccination rejected successfully.');
    }

    public function approvePrescription($id)
    {
        $prescription = PrescriptionRefill::findOrFail($id);
        
        if ($prescription->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $prescription->status = 'approved';
        $prescription->save();

        return redirect()->back()->with('success', 'Prescription approved successfully.');
    }

    public function rejectPrescription($id)
    {
        $prescription = PrescriptionRefill::findOrFail($id);
        
        if ($prescription->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $prescription->status = 'rejected';
        $prescription->save();

        return redirect()->back()->with('success', 'Prescription rejected successfully.');
    }

    public function patients()
    {
        $doctor = Auth::user();
        $patients = User::whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with(['appointments' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->latest();
            }])
            ->paginate(10);

        return view('doctor.patients.index', compact('patients'));
    }

    public function createPatient()
    {
        return view('doctor.patients.create');
    }

    public function storePatient(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|max:20',
                'gender' => 'required|in:male,female,other',
                'date_of_birth' => 'required|date',
                'address' => 'required|string|max:255',
                'medical_history' => 'nullable|string',
                'notes' => 'nullable|string',
                'age' => 'nullable|integer'
            ]);

            // Check if a patient with this phone number already exists
            $existingPatient = Patient::where('phone', $validated['phone'])->first();
            if ($existingPatient) {
                return redirect()->back()->withInput()
                    ->withErrors(['phone' => 'A patient with this phone number already exists.']);
            }

            // Create a new user with patient role
            $patient = new User();
            $patient->name = $validated['name'];
            $patient->email = $validated['email'];
            $patient->phone = $validated['phone'];
            $patient->gender = $validated['gender'];
            $patient->date_of_birth = $validated['date_of_birth'];
            $patient->address = $validated['address'];
            $patient->role = 'patient'; // Ensure patient role is set
            $patient->password = Hash::make(Str::random(10)); // Generate a random password
            $patient->save();

            // Create patient record
            $patientRecord = new Patient();
            $patientRecord->user_id = $patient->id;
            $patientRecord->name = $validated['name'];
            $patientRecord->email = $validated['email'];
            $patientRecord->phone = $validated['phone'];
            $patientRecord->gender = $validated['gender'];
            $patientRecord->address = $validated['address'];
            $patientRecord->age = $validated['age'] ?? $this->calculateAge($validated['date_of_birth']);
            $patientRecord->medical_history = $validated['medical_history'] ?? null;
            $patientRecord->notes = $validated['notes'] ?? null;
            $patientRecord->save();

            // Associate the patient with the doctor
            $doctor = Auth::user();
            $doctor->patients()->attach($patient->id);

            return redirect()->route('doctor.patients.index')
                ->with('success', 'Patient created successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            // Check if it's a duplicate entry error
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->withInput()
                    ->withErrors(['phone' => 'A patient with this phone number already exists.']);
            }
            
            // For other database errors
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'An error occurred while creating the patient. Please try again.']);
        } catch (\Exception $e) {
            // For any other exceptions
            return redirect()->back()->withInput()
                ->withErrors(['error' => 'An error occurred while creating the patient. Please try again.']);
        }
    }

    /**
     * Calculate age from date of birth
     * 
     * @param string $dateOfBirth
     * @return int
     */
    private function calculateAge($dateOfBirth)
    {
        return date_diff(date_create($dateOfBirth), date_create('today'))->y;
    }

    public function showPatient($id)
    {
        $doctor = Auth::user();
        $patient = User::findOrFail($id);
        
        // Check if this patient is associated with the doctor
        $isAssociated = Appointment::where('doctor_id', $doctor->id)
            ->where('user_id', $patient->id)
            ->exists();
            
        if (!$isAssociated) {
            return redirect()->route('doctor.patients.index')
                ->with('error', 'You do not have permission to view this patient.');
        }
        
        // Get patient's appointments with this doctor
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('user_id', $patient->id)
            ->latest()
            ->get();
            
        // Get patient's health screenings with this doctor
        $screenings = HealthScreeningBooking::where('doctor_id', $doctor->id)
            ->where('user_id', $patient->id)
            ->with('test')
            ->latest()
            ->get();
            
        // Get patient's vaccinations with this doctor
        $vaccinations = VaccinationBooking::where('doctor_id', $doctor->id)
            ->where('user_id', $patient->id)
            ->with('vaccine')
            ->latest()
            ->get();

        return view('doctor.patients.show', compact('patient', 'appointments', 'screenings', 'vaccinations'));
    }

    public function editPatient($id)
    {
        $doctor = Auth::user();
        $patient = User::findOrFail($id);
        
        // Check if this patient is associated with the doctor
        $isAssociated = Appointment::where('doctor_id', $doctor->id)
            ->where('user_id', $patient->id)
            ->exists();
            
        if (!$isAssociated) {
            return redirect()->route('doctor.patients.index')
                ->with('error', 'You do not have permission to edit this patient.');
        }

        return view('doctor.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, $id)
    {
        $doctor = Auth::user();
        $patient = User::findOrFail($id);
        
        // Check if this patient is associated with the doctor
        $isAssociated = Appointment::where('doctor_id', $doctor->id)
            ->where('user_id', $patient->id)
            ->exists();
            
        if (!$isAssociated) {
            return redirect()->route('doctor.patients.index')
                ->with('error', 'You do not have permission to update this patient.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $patient->name = $validated['name'];
        $patient->phone = $validated['phone'];
        $patient->gender = $validated['gender'];
        $patient->date_of_birth = $validated['date_of_birth'];
        $patient->address = $validated['address'];
        
        if (isset($validated['notes'])) {
            $patient->notes = $validated['notes'];
        }
        
        $patient->save();

        return redirect()->route('doctor.patients.show', $patient->id)
            ->with('success', 'Patient updated successfully');
    }

    public function prescriptions()
    {
        $doctor = Auth::user();
        $prescriptions = Prescription::where('doctor_id', $doctor->id)
            ->with(['patient', 'medications'])
            ->latest()
            ->paginate(10);

        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    public function healthScreenings()
    {
        $doctor = Auth::user();
        $screenings = HealthScreeningBooking::where('doctor_id', $doctor->id)
            ->with(['user', 'test'])
            ->latest()
            ->paginate(10);

        return view('doctor.screenings.index', compact('screenings'));
    }

    public function screeningReports()
    {
        $doctor = Auth::user();
        $screenings = HealthScreeningBooking::where('doctor_id', $doctor->id)
            ->with(['user', 'test'])
            ->latest()
            ->get();

        $tests = HealthScreening::orderBy('name')->get();
        
        // Calculate test type distribution
        $testTypeDistribution = $screenings->groupBy(function($item) {
            return $item->test->name;
        })->map(function($group) {
            return $group->count();
        });

        // Calculate monthly trend data
        $monthlyTrend = $screenings->groupBy(function($item) {
            return $item->created_at->format('Y-m');
        })->map(function($group) {
            return $group->count();
        });

        // Get the last 12 months including current month
        $dates = collect([]);
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i)->format('Y-m');
            $dates->push($date);
        }

        $chartData = [
            'testTypes' => [
                'labels' => $testTypeDistribution->keys()->toArray(),
                'data' => $testTypeDistribution->values()->toArray()
            ],
            'monthlyTrend' => [
                'labels' => $dates->reverse()->toArray(),
                'data' => $dates->reverse()->map(function($date) use ($monthlyTrend) {
                    return $monthlyTrend->has($date) ? $monthlyTrend[$date] : 0;
                })->toArray()
            ]
        ];

        $stats = [
            'total' => $screenings->count(),
            'completed' => $screenings->where('status', 'completed')->count(),
            'pending' => $screenings->where('status', 'pending')->count(),
            'approved' => $screenings->where('status', 'approved')->count(),
            'rejected' => $screenings->where('status', 'rejected')->count(),
        ];

        return view('doctor.screenings.reports', compact('screenings', 'stats', 'tests', 'chartData'));
    }

    public function showScreening($id)
    {
        $doctor = Auth::user();
        $screening = HealthScreeningBooking::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with(['user', 'test'])
            ->firstOrFail();

        return view('doctor.screenings.show', compact('screening'));
    }

    public function messages()
    {
        $doctor = Auth::user();
        $messages = Message::where('doctor_id', $doctor->id)
            ->orWhere('user_id', $doctor->id)
            ->with(['user', 'doctor'])
            ->latest()
            ->paginate(10);

        // Group messages by conversation
        $conversations = [];
        foreach ($messages as $message) {
            $otherUserId = $message->user_id == $doctor->id ? $message->doctor_id : $message->user_id;
            $otherUser = $message->user_id == $doctor->id ? $message->doctor : $message->user;
            
            if (!isset($conversations[$otherUserId])) {
                $conversations[$otherUserId] = [
                    'user' => $otherUser,
                    'messages' => [],
                    'unread_count' => 0
                ];
            }
            $conversations[$otherUserId]['messages'][] = $message;
            
            // Count unread messages
            if ($message->user_id != $doctor->id && !$message->read_at) {
                $conversations[$otherUserId]['unread_count']++;
            }
        }

        return view('doctor.messages.index', compact('messages', 'conversations'));
    }

    public function conversation($userId)
    {
        $doctor = Auth::user();
        
        // Get all messages between the doctor and the user
        $messages = Message::where(function($query) use ($doctor, $userId) {
            $query->where('user_id', $userId)
                  ->where('doctor_id', $doctor->id);
        })
        ->orWhere(function($query) use ($doctor, $userId) {
            $query->where('user_id', $doctor->id)
                  ->where('doctor_id', $userId);
        })
        ->with(['user', 'doctor'])
        ->latest()
        ->paginate(10);

        // Mark messages as read if they were unread
        Message::where('doctor_id', $doctor->id)
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('doctor.messages.conversation', compact('messages', 'userId'));
    }

    public function sendMessage(Request $request)
    {
        $doctor = Auth::user();
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
        ]);

        // Create the message
        $message = new Message([
            'doctor_id' => $doctor->id,
            'user_id' => $validated['recipient_id'],
            'content' => $validated['content'],
            'is_from_user' => false,  // Message is from doctor, not user
        ]);

        $message->save();

        return redirect()->route('doctor.messages.conversation', $validated['recipient_id'])
            ->with('success', 'Message sent successfully');
    }

    public function showAddPatientForm()
    {
        return view('doctor.patients.add');
    }

    public function addPatient(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string|max:500',
        ]);

        // Create new user/patient
        $patient = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'] ?? null,
            'role' => 'patient', // Ensure patient role is set
        ]);

        // Optional: Create a ClinicPatient entry if needed
        ClinicPatient::create([
            'user_id' => $patient->id,
            'doctor_id' => Auth::id(),
            // Add any additional fields as needed
        ]);

        return redirect()->route('doctor.patients')->with('success', 'Patient added successfully');
    }

    public function availability()
    {
        $doctor = Auth::user();
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->get();

        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        return view('doctor.availability', compact('schedules', 'days'));
    }

    public function updateAvailability(Request $request)
    {
        $doctor = Auth::user();
        
        // Validate the incoming data
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:0,6',
            'schedules.*.start_time' => 'required|string',
            'schedules.*.end_time' => 'required|string',
        ]);

        // Delete existing schedules for this doctor
        DoctorSchedule::where('doctor_id', $doctor->id)->delete();

        // Create new schedules
        foreach ($validated['schedules'] as $schedule) {
            DoctorSchedule::create([
                'doctor_id' => $doctor->id,
                'day_of_week' => $schedule['day_of_week'],
                'start_time' => $schedule['start_time'],
                'end_time' => $schedule['end_time'],
            ]);
        }

        return redirect()->route('doctor.availability.index')
            ->with('success', 'Availability schedule updated successfully');
    }

    public function approveScreening($id)
    {
        $screening = HealthScreeningBooking::findOrFail($id);
        
        // Check if the doctor has permission to approve this screening
        if ($screening->doctor_id != Auth::id()) {
            return redirect()->route('doctor.screenings.index')
                ->with('error', 'You do not have permission to approve this screening.');
        }
        
        $screening->status = 'approved';
        $screening->save();
        
        // Notify the patient
        $screening->user->notify(new HealthScreeningApprovedNotification($screening));
        
        return redirect()->route('doctor.screenings.index')
            ->with('success', 'Health screening has been approved successfully.');
    }
    
    public function rejectScreening($id)
    {
        $screening = HealthScreeningBooking::findOrFail($id);
        
        // Check if the doctor has permission to reject this screening
        if ($screening->doctor_id != Auth::id()) {
            return redirect()->route('doctor.screenings.index')
                ->with('error', 'You do not have permission to reject this screening.');
        }
        
        $screening->status = 'rejected';
        $screening->save();
        
        // Notify the patient
        $screening->user->notify(new HealthScreeningRejectedNotification($screening));
        
        return redirect()->route('doctor.screenings.index')
            ->with('success', 'Health screening has been rejected.');
    }

    public function vaccinations()
    {
        $doctor = Auth::user();
        $vaccinations = VaccinationBooking::where('doctor_id', $doctor->id)
            ->with(['user', 'vaccine'])
            ->latest()
            ->paginate(10);

        return view('doctor.vaccinations.index', compact('vaccinations'));
    }

    public function showVaccination($id)
    {
        $doctor = Auth::user();
        $vaccination = VaccinationBooking::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with(['user', 'vaccine'])
            ->firstOrFail();

        return view('doctor.vaccinations.show', compact('vaccination'));
    }

    public function acceptVaccination($id)
    {
        $doctor = Auth::user();
        $vaccination = VaccinationBooking::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $vaccination->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        // Notify the user
        $vaccination->user->notify(new VaccinationApproved($vaccination));

        return redirect()->route('doctor.vaccinations.index')
            ->with('success', 'Vaccination booking has been approved.');
    }

    public function declineVaccination($id)
    {
        $doctor = Auth::user();
        $vaccination = VaccinationBooking::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $vaccination->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => request('rejection_reason')
        ]);

        // Notify the user
        $vaccination->user->notify(new VaccinationRejected($vaccination));

        return redirect()->route('doctor.vaccinations.index')
            ->with('success', 'Vaccination booking has been rejected.');
    }

    public function finishVaccination($id)
    {
        $doctor = Auth::user();
        $vaccination = VaccinationBooking::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->firstOrFail();

        $vaccination->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Notify the user
        $vaccination->user->notify(new VaccinationCompleted($vaccination));

        return redirect()->route('doctor.vaccinations.index')
            ->with('success', 'Vaccination has been marked as completed.');
    }

    /**
     * Display user notifications for doctor to manage
     *
     * @return \Illuminate\Http\Response
     */
    public function userNotifications()
    {
        $doctor = Auth::user();
        
        // Get patients associated with this doctor
        $patientIds = Appointment::where('doctor_id', $doctor->id)
            ->pluck('user_id')
            ->unique();
        
        // Get notifications sent to these patients
        $notifications = \App\Models\Notification::whereIn('user_id', $patientIds)
            ->where('doctor_id', $doctor->id)
            ->latest()
            ->paginate(15);
        
        // Get patients for the dropdown
        $patients = User::whereIn('id', $patientIds)->get();
        
        return view('doctor.notifications.index', compact('notifications', 'patients'));
    }
    
    /**
     * Send a notification to a user
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendUserNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        $doctor = Auth::user();
        $user = User::findOrFail($request->user_id);
        
        // Check if this user is a patient of this doctor
        $isPatient = Appointment::where('doctor_id', $doctor->id)
            ->where('user_id', $user->id)
            ->exists();
        
        if (!$isPatient) {
            return redirect()->route('doctor.user-notifications')
                ->with('error', 'You can only send notifications to your patients.');
        }
        
        // Create the notification
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'doctor_id' => $doctor->id,
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'is_read' => false,
            'related_id' => null
        ]);
        
        return redirect()->route('doctor.user-notifications')
            ->with('success', 'Notification has been sent successfully.');
    }
    
    /**
     * Display user pharmacy requests for doctor to manage
     *
     * @return \Illuminate\Http\Response
     */
    public function userPharmacy()
    {
        $doctor = Auth::user();
        
        // Get pharmacy requests related to this doctor's patients
        $prescriptionRefills = PrescriptionRefill::where(function($query) use ($doctor) {
            // Get refills linked to prescriptions this doctor created
            $query->whereHas('prescription', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            });
            
            // Also get refills that are directly associated with this doctor's patients
            $query->orWhere(function($query) use ($doctor) {
                $query->whereHas('user', function($query) use ($doctor) {
                    $query->whereHas('appointments', function($query) use ($doctor) {
                        $query->where('doctor_id', $doctor->id);
                    });
                });
            });
        })
        ->with(['user', 'prescription'])
        ->latest()
        ->paginate(15);
        
        return view('doctor.pharmacy.index', compact('prescriptionRefills'));
    }
    
    /**
     * Show details of a specific pharmacy request
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function showUserPharmacyRequest($id)
    {
        $doctor = Auth::user();
        $prescriptionRefill = PrescriptionRefill::with(['user', 'prescription'])
            ->findOrFail($id);
        
        // Check if this prescription refill is related to this doctor
        if ($prescriptionRefill->prescription->doctor_id != $doctor->id) {
            return redirect()->route('doctor.user-pharmacy')
                ->with('error', 'You do not have permission to view this pharmacy request.');
        }
        
        return view('doctor.pharmacy.show', compact('prescriptionRefill'));
    }
    
    /**
     * Approve a pharmacy request
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function approveUserPharmacyRequest($id)
    {
        $doctor = Auth::user();
        $prescriptionRefill = PrescriptionRefill::findOrFail($id);
        
        // Check if this prescription refill is related to this doctor
        if ($prescriptionRefill->prescription->doctor_id != $doctor->id) {
            return redirect()->route('doctor.user-pharmacy')
                ->with('error', 'You do not have permission to approve this pharmacy request.');
        }
        
        $prescriptionRefill->update(['status' => 'approved', 'approved_at' => now()]);
        
        // Notify the user
        $prescriptionRefill->user->notify(new \App\Notifications\PrescriptionRefillApproved($prescriptionRefill));
        
        return redirect()->route('doctor.user-pharmacy')
            ->with('success', 'Pharmacy request has been approved successfully.');
    }
    
    /**
     * Reject a pharmacy request
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function rejectUserPharmacyRequest(Request $request, $id)
    {
        $doctor = Auth::user();
        $prescriptionRefill = PrescriptionRefill::findOrFail($id);
        
        // Check if this prescription refill is related to this doctor
        if ($prescriptionRefill->prescription->doctor_id != $doctor->id) {
            return redirect()->route('doctor.user-pharmacy')
                ->with('error', 'You do not have permission to reject this pharmacy request.');
        }
        
        $prescriptionRefill->update([
            'status' => 'rejected', 
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);
        
        // Notify the user
        $prescriptionRefill->user->notify(new \App\Notifications\PrescriptionRefillRejected($prescriptionRefill));
        
        return redirect()->route('doctor.user-pharmacy')
            ->with('success', 'Pharmacy request has been rejected successfully.');
    }

    /**
     * Show a patient's profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPatientProfile($id)
    {
        $patient = User::with(['patientProfile', 'medicalHistory', 'allergies', 'medications', 'familyHistory', 'lifestyleInfo'])
            ->findOrFail($id);
            
        // Get upcoming appointments
        $upcomingAppointments = Appointment::where('user_id', $patient->id)
            ->where('status', '!=', 'cancelled')
            ->where('appointment_date', '>=', now())
            ->with('doctor', 'clinic')
            ->orderBy('appointment_date')
            ->take(5)
            ->get();
            
        // Get past appointments
        $pastAppointments = Appointment::where('user_id', $patient->id)
            ->where('appointment_date', '<', now())
            ->with('doctor', 'clinic')
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();
            
        // Get prescriptions
        $prescriptions = Prescription::where('patient_id', $patient->id)
            ->with('doctor', 'medications')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('doctor.patients.profile', [
            'patient' => $patient,
            'upcomingAppointments' => $upcomingAppointments,
            'pastAppointments' => $pastAppointments,
            'prescriptions' => $prescriptions,
        ]);
    }
}