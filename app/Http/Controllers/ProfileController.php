<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\VaccinationBooking;
use App\Models\HealthScreeningBooking;
use App\Models\Appointment;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all appointments
        $appointments = Appointment::where('user_id', $user->id)
            ->with(['doctor', 'clinic' => function ($query) {
                $query->withDefault();
            }])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        // Get all health screenings
        $healthScreeningBookings = HealthScreeningBooking::where('user_id', $user->id)
            ->with(['healthScreening', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        // Get all vaccination bookings
        $vaccinationBookings = VaccinationBooking::where('user_id', $user->id)
            ->with(['vaccine', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('profile.index', compact(
            'user',
            'appointments',
            'healthScreeningBookings',
            'vaccinationBookings'
        ));
    }

    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        
        // Fetch vaccination bookings
        $vaccinationBookings = VaccinationBooking::where('user_id', $user->id)
            ->with(['vaccine', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();
        
        // Fetch health screening bookings
        $healthScreeningBookings = HealthScreeningBooking::where('user_id', $user->id)
            ->with(['healthScreening', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();
        
        return view('profile.show', compact('user', 'vaccinationBookings', 'healthScreeningBookings'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validatedData);

        return redirect()->route('profile.edit')
            ->with('status', 'Profile updated successfully.');
    }

    /**
     * Change the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')
            ->with('status', 'Password changed successfully.');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('status', 'Your account has been deleted.');
    }

    /**
     * Display the admin profile.
     *
     * @return \Illuminate\View\View
     */
    public function adminShow()
    {
        return view('admin.profile.index');
    }

    public function adminUpdate(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function adminChangePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}