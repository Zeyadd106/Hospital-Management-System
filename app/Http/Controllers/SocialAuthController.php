<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', "Failed to connect to $provider. Please try again.");
        }
    }

    public function handleCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            $email = $socialUser->getEmail();

            // Check if user exists
            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                // Allow login for user and doctor roles
                if ($existingUser->role === 'user' || $existingUser->role === 'doctor') {
                    Auth::login($existingUser);
                    
                    // Redirect based on role
                    $route = $existingUser->role === 'doctor' 
                        ? 'doctor.dashboard' 
                        : 'dashboard';
                    
                    return redirect()->route($route);
                }
                
                return redirect()->route('login')
                    ->with('error', 'You are not allowed to login with ' . ucfirst($provider) . '.');
            }

            // Create new user with role 'user' by default
            $newUser = User::create([
                'name' => $socialUser->getName(),
                'email' => $email,
                'role' => 'user',
                'password' => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
            ]);

            Auth::login($newUser);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', "Failed to login with $provider. Please try again.");
        }
    }
}
