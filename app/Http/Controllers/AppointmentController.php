<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\User;
use App\Notifications\AppointmentConfirmedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->with('doctor')
            ->orderBy('appointment_date', 'desc')
            ->get();
        
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $doctors = Doctor::where('is_available', true)->get();
        $clinics = Clinic::where('status', 'active')->get();
        
        return view('appointments.create', compact('doctors', 'clinics'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'purpose' => 'required|string|max:255'
        ]);

        // Get the doctor's user ID
        $doctor = Doctor::find($validatedData['doctor_id']);
        if (!$doctor || !$doctor->user_id) {
            return redirect()->back()->withErrors(['doctor_id' => 'Invalid doctor selected']);
        }

        // Store the doctor's user_id as doctor_id in appointments table
        $validatedData['doctor_id'] = $doctor->user_id;
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'pending';
        $validatedData['purpose'] = $request->purpose;

        $appointment = Appointment::create($validatedData);
        
        // Notify the doctor using our custom notification
        $notification = new AppointmentConfirmedNotification($appointment);
        $notification->send($doctor->user);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully.');
    }

    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::with(['doctor', 'clinic'])
            ->findOrFail($id);

        // Ensure the appointment belongs to the logged-in user
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Only allow editing pending appointments
        if ($appointment->status !== 'pending') {
            return redirect()->route('appointments.index')
                ->with('warning', 'This appointment cannot be edited.');
        }

        $doctors = Doctor::where('is_available', true)->get();
        $clinics = Clinic::where('status', 'active')->get();

        return view('appointments.edit', compact('appointment', 'doctors', 'clinics'));
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Ensure the appointment belongs to the logged-in user
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Only allow updating pending appointments
        if ($appointment->status !== 'pending') {
            return redirect()->route('appointments.index')
                ->with('warning', 'This appointment cannot be modified.');
        }

        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'purpose' => 'required|string|max:255'
        ]);

        $appointment->update($validatedData);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Cancel the specified appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Ensure the appointment belongs to the logged-in user
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Update the appointment status to cancelled
        $appointment->status = 'cancelled';
        $appointment->cancelled_at = now();
        $appointment->cancellation_reason = 'User cancelled the appointment';
        $appointment->save();

        // Notify the doctor
        if ($appointment->doctor) {
            $appointment->doctor->notify(new AppointmentCancelledNotification($appointment));
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment has been cancelled successfully.');
    }

    /**
     * Verify the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Ensure the appointment belongs to the logged-in user
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        // Only allow verifying pending appointments
        if ($appointment->status === 'pending') {
            $appointment->update(['status' => 'confirmed']);

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment verified successfully.');
        }

        return redirect()->route('appointments.index')
            ->with('warning', 'This appointment cannot be verified.');
    }

    /**
     * Verify the appointment using confirmation code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyAppointment(Request $request)
    {
        $request->validate([
            'confirmation_code' => 'required|string'
        ]);

        // Find the appointment by confirmation code
        $appointment = Appointment::where('confirmation_code', $request->confirmation_code)
            ->where('user_id', Auth::id())
            ->first();

        if ($appointment) {
            // Update appointment status if found
            $appointment->update(['status' => 'confirmed']);
            return redirect()->route('appointments.index')
                ->with('success', 'Appointment verified successfully.');
        }

        return redirect()->route('appointments.index')
            ->with('error', 'Invalid confirmation code.');
    }
}