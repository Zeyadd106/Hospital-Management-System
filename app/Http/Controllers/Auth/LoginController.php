<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('Login attempt', [
            'email' => $request->email,
            'role' => User::where('email', $request->email)->value('role'),
            'user_exists' => User::where('email', $request->email)->exists()
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::warning('Login failed - User not found', ['email' => $request->email]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.'
            ])->withInput($request->only('email'));
        }

        // Attempt to login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Log::info('Login successful', [
                'email' => $request->email,
                'role' => $user->role
            ]);

            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'doctor':
                    return redirect()->route('doctor.dashboard');
                case 'pharmacy':
                    return redirect()->route('pharmacy.dashboard');
                case 'user':
                    return redirect()->route('dashboard');
                default:
                    return redirect()->route('home');
            }
        } else {
            Log::warning('Login failed - Invalid credentials', [
                'email' => $request->email,
                'role' => $user->role
            ]);

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.'
            ])->withInput($request->only('email'));
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
