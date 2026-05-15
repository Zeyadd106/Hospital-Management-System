<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'doctor']);
    }

    public function index()
    {
        $doctor = Auth::user()->doctorProfile;
        return view('doctor.profile.index', compact('doctor'));
    }

    public function show()
    {
        return $this->index();
    }

    public function edit()
    {
        $doctor = Auth::user()->doctorProfile ?? new DoctorProfile();
        return view('doctor.profile.edit', compact('doctor'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'specialty' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'qualification' => 'required|string|max:255',
            'about' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        // Check if doctor profile exists, create if it doesn't
        $doctor = $user->doctorProfile ?? new DoctorProfile(['user_id' => $user->id]);
        
        // Manually set the specialty field
        $doctor->specialty = $request->specialty;
        $doctor->qualification = $request->qualification;
        $doctor->experience_years = $request->experience_years;
        $doctor->about = $request->about;
        $doctor->save();

        return redirect()->route('doctor.profile')
            ->with('success', 'Profile updated successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('doctor.profile')
            ->with('success', 'Password updated successfully');
    }
}
