@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title">Welcome to MediCare</h2>
                    <p class="card-text">Your trusted healthcare partner for all your medical needs.</p>
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    @auth
                        <p>Welcome back, {{ Auth::user()->name }}!</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Vaccinations</h5>
                                        <p class="card-text">Protect yourself and your loved ones with our comprehensive vaccination services.</p>
                                        <a href="{{ route('vaccination.index') }}" class="btn btn-primary">Learn More</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Health Screenings</h5>
                                        <p class="card-text">Early detection is key. Our health screening services help identify potential issues.</p>
                                        <a href="{{ route('health-screenings.index') }}" class="btn btn-primary">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p>Please log in to access all features.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Vaccinations</h5>
                                        <p class="card-text">Protect yourself and your loved ones with our comprehensive vaccination services.</p>
                                        <a href="{{ route('login') }}" class="btn btn-primary">Login to Learn More</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title">Health Screenings</h5>
                                        <p class="card-text">Early detection is key. Our health screening services help identify potential issues.</p>
                                        <a href="{{ route('login') }}" class="btn btn-primary">Login to Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    Quick Actions
                </div>
                <div class="card-body">
                    @auth
                        @if (Auth::user()->hasRole('doctor'))
                            <div class="mb-3">
                                <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary w-100">Doctor Dashboard</a>
                            </div>
                        @elseif (Auth::user()->hasRole('pharmacy_admin'))
                            <div class="mb-3">
                                <a href="{{ route('pharmacy.dashboard') }}" class="btn btn-outline-primary w-100">Pharmacy Dashboard</a>
                            </div>
                        @endif
                        <div class="mb-3">
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-primary w-100">View Profile</a>
                        </div>
                        <div class="mb-3">
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary w-100">View Appointments</a>
                        </div>
                    @else
                        <div class="mb-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login</a>
                        </div>
                        <div class="mb-3">
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
