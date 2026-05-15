<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'doctor']);
    }

    public function index()
    {
        $doctorId = Auth::id();
        $schedules = DoctorSchedule::where('doctor_id', $doctorId)
            ->orderBy('day_of_week')
            ->get();

        return view('doctor.schedule.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive'
        ]);

        $schedule = new DoctorSchedule();
        $schedule->doctor_id = Auth::id();
        $schedule->day_of_week = $request->day_of_week;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->status = $request->status;
        $schedule->save();

        return redirect()->route('doctor.schedule')
            ->with('success', 'Schedule added successfully');
    }

    public function edit($id)
    {
        $schedule = DoctorSchedule::where('doctor_id', Auth::id())
            ->findOrFail($id);

        return view('doctor.schedule.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive'
        ]);

        $schedule = DoctorSchedule::where('doctor_id', Auth::id())
            ->findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('doctor.schedule')
            ->with('success', 'Schedule updated successfully');
    }

    public function destroy($id)
    {
        $schedule = DoctorSchedule::where('doctor_id', Auth::id())
            ->findOrFail($id);
        $schedule->delete();

        return redirect()->route('doctor.schedule')
            ->with('success', 'Schedule deleted successfully');
    }
}
