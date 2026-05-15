<?php

namespace App\Http\Controllers;

use App\Models\HealthScreeningBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorHealthScreeningController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'doctor']);
    }

    public function index(Request $request)
    {
        $query = HealthScreeningBooking::where('doctor_id', Auth::id())
            ->with('user', 'test', 'clinic');

        // Apply filters
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('appointment_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('appointment_date', '<=', $request->end_date);
        }

        $bookings = $query->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('doctor.health_screenings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = HealthScreeningBooking::where('doctor_id', Auth::id())
            ->with('user', 'test', 'clinic')
            ->findOrFail($id);

        return view('doctor.health_screenings.show', compact('booking'));
    }

    public function accept($id)
    {
        $booking = HealthScreeningBooking::where('doctor_id', Auth::id())
            ->findOrFail($id);

        $booking->status = 'confirmed';
        $booking->confirmed_at = now();
        $booking->save();

        return redirect()->route('doctor.health_screenings')->with('success', 'Health screening booking confirmed successfully');
    }

    public function reject($id)
    {
        $booking = HealthScreeningBooking::where('doctor_id', Auth::id())
            ->findOrFail($id);

        $booking->status = 'rejected';
        $booking->rejected_at = now();
        $booking->save();

        return redirect()->route('doctor.health_screenings')->with('success', 'Health screening booking rejected');
    }

    public function complete($id)
    {
        $booking = HealthScreeningBooking::where('doctor_id', Auth::id())
            ->findOrFail($id);

        $booking->status = 'completed';
        $booking->completed_at = now();
        $booking->save();

        return redirect()->route('doctor.health_screenings')->with('success', 'Health screening booking completed');
    }

    public function generateReports(Request $request)
    {
        $query = HealthScreeningBooking::where('doctor_id', Auth::id())
            ->with('user', 'test', 'clinic');

        // Apply filters if needed
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('appointment_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('appointment_date', '<=', $request->end_date);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('appointment_date', 'desc')->get();

        // Generate PDF or Excel report
        return view('doctor.health_screenings.reports', compact('bookings'));
    }
}
