@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center mb-0">Health Screening Booking Successful</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h4 class="mb-3">Your Booking Details</h4>
                    
                    <div class="mb-3">
                        <strong>Screening Type:</strong> {{ $screening->name }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Clinic:</strong> {{ $clinic->name }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Appointment Date:</strong> {{ $booking->appointment_date->format('F j, Y') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Appointment Time:</strong> {{ $booking->appointment_time }}
                    </div>
                    
                    <div class="mb-4">
                        <strong>Confirmation Code:</strong> <span class="badge bg-primary">{{ $booking->confirmation_code }}</span>
                    </div>
                    
                    <p class="mb-4">
                        Please keep your confirmation code safe. You will need it for verification.
                    </p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('health-screenings.index') }}" class="btn btn-primary">
                            View All Bookings
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
