@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Appointment Details</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }} p-2 fs-6">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Doctor</h5>
                            <p>{{ $appointment->doctor ? $appointment->doctor->name : 'Doctor Not Assigned' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Clinic</h5>
                            <p>{{ $appointment->clinic ? $appointment->clinic->name : 'Clinic Not Assigned' }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Date</h5>
                            <p>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Time</h5>
                            <p>{{ $appointment->appointment_time }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Purpose</h5>
                            <p>{{ $appointment->purpose }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Type</h5>
                            <p>{{ ucfirst($appointment->type) }}</p>
                        </div>
                    </div>
                    
                    @if($appointment->notes)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Notes</h5>
                            <p>{{ $appointment->notes }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($appointment->status === 'pending')
                    <div class="mt-4">
                        <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                Cancel Appointment
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
