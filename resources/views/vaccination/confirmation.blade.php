@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    
                    <h2 class="mb-4">Vaccination Appointment Confirmed!</h2>
                    
                    <p class="lead">Thank you for booking your vaccination appointment with MediCare.</p>
                    
                    <div class="alert alert-info my-4">
                        <p class="mb-1"><strong>Confirmation Code:</strong></p>
                        <h3 class="mb-0">{{ $booking->confirmation_code }}</h3>
                        <p class="small mt-2 mb-0">Please save this code for your records.</p>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title h5 mb-3">Appointment Details</h4>
                            
                            <div class="row mb-2">
                                <div class="col-md-6 text-md-end"><strong>Vaccine:</strong></div>
                                <div class="col-md-6 text-md-start">{{ $booking->vaccine->name }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-6 text-md-end"><strong>Date:</strong></div>
                                <div class="col-md-6 text-md-start">{{ \Carbon\Carbon::parse($booking->appointment_date)->format('l, F j, Y') }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-6 text-md-end"><strong>Time:</strong></div>
                                <div class="col-md-6 text-md-start">{{ $booking->time_slot }}</div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-md-6 text-md-end"><strong>Status:</strong></div>
                                <div class="col-md-6 text-md-start">
                                    <span class="badge bg-{{ $booking->status_badge }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p>We've sent a confirmation email to <strong>{{ $booking->email }}</strong> with all the details.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('vaccinations.booking.index') }}" class="btn btn-primary">View My Bookings</a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary ms-2">Return to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

