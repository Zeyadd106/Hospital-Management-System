<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\VaccinationBooking;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorVaccinationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:doctor']);
    }

    /**
     * List vaccination bookings for the current doctor
     */
    public function index()
    {
        // Find the current doctor
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        // Get vaccination bookings for this doctor
        $bookings = VaccinationBooking::where('doctor_id', $doctor->id)
            ->with(['user', 'vaccine', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('doctor.vaccinations.index', compact('bookings'));
    }

    /**
     * Show details of a specific vaccination booking
     */
    public function show($id)
    {
        // Find the current doctor
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        // Find the vaccination booking
        $booking = VaccinationBooking::where('doctor_id', $doctor->id)
            ->with(['user', 'vaccine', 'clinic'])
            ->findOrFail($id);

        return view('doctor.vaccinations.show', compact('booking'));
    }

    /**
     * Accept a vaccination booking
     */
    public function accept($id)
    {
        // Find the current doctor
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        // Find and update the vaccination booking
        $booking = VaccinationBooking::where('doctor_id', $doctor->id)
            ->findOrFail($id);

        $booking->status = 'confirmed';
        $booking->confirmed_at = now();
        $booking->save();

        return redirect()->route('doctor.vaccinations.index')
            ->with('success', 'Vaccination booking confirmed successfully');
    }

    /**
     * Reject a vaccination booking
     */
    public function reject($id, Request $request)
    {
        // Find the current doctor
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        // Find and update the vaccination booking
        $booking = VaccinationBooking::where('doctor_id', $doctor->id)
            ->findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $booking->status = 'rejected';
        $booking->cancelled_at = now();
        $booking->notes = $request->rejection_reason;
        $booking->save();

        return redirect()->route('doctor.vaccinations.index')
            ->with('success', 'Vaccination booking rejected successfully');
    }

    /**
     * Mark a vaccination booking as completed
     */
    public function complete($id)
    {
        // Find the current doctor
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        // Find and update the vaccination booking
        $booking = VaccinationBooking::where('doctor_id', $doctor->id)
            ->findOrFail($id);

        $booking->status = 'completed';
        $booking->completed_at = now();
        $booking->save();

        // Create a vaccination report
        $report = Report::create([
            'user_id' => $booking->user_id,
            'doctor_id' => $doctor->id,
            'type' => 'vaccination',
            'data' => json_encode([
                'vaccination_booking_id' => $booking->id,
                'vaccine_name' => $booking->vaccine->name,
                'administered_at' => now(),
                'notes' => 'Vaccination completed successfully'
            ])
        ]);

        return redirect()->route('doctor.vaccinations.index')
            ->with('success', 'Vaccination booking completed and report generated');
    }

    /**
     * Generate vaccination reports
     */
    public function generateReports(Request $request)
    {
        // Find the current doctor
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        // Base query for vaccination reports
        $query = VaccinationBooking::where('doctor_id', $doctor->id)
            ->with(['user', 'vaccine']);

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->where('appointment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('appointment_date', '<=', $request->end_date);
        }

        // Get the filtered bookings
        $bookings = $query->get();

        // Generate summary statistics
        $stats = [
            'total_bookings' => $bookings->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'rejected_bookings' => $bookings->where('status', 'rejected')->count(),
        ];

        // Render view or download report
        return view('doctor.vaccinations.reports', compact('bookings', 'stats'));
    }
}
