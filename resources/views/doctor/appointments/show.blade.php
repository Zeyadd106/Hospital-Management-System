@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Appointment Details</h2>
        <a href="{{ route('doctor.appointments.today') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Appointments
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Appointment Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-muted">Patient Details</h5>
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $appointment->patient->avatar ?? asset('images/default-avatar.png') }}" 
                             class="rounded-circle me-3" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <h4 class="mb-1">{{ $appointment->patient->name }}</h4>
                            <p class="text-muted mb-0">{{ $appointment->patient->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Appointment Details</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="ps-0">Clinic</th>
                            <td>{{ $appointment->clinic->name }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Date</th>
                            <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Time</th>
                            <td>{{ $appointment->appointment_time }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Status</th>
                            <td>
                                <span class="badge bg-{{ 
                                    $appointment->status === 'confirmed' ? 'success' : 
                                    ($appointment->status === 'pending' ? 'warning' : 'secondary') 
                                }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($appointment->prescriptions->isNotEmpty())
                <div class="mt-4">
                    <h5 class="text-muted mb-3">
                        <i class="fas fa-prescription me-2 text-primary"></i>Prescriptions
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Medication</th>
                                    <th>Dosage</th>
                                    <th>Frequency</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointment->prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->medications->pluck('name')->implode(', ') }}</td>
                                        <td>{{ $prescription->medications->pluck('dosage')->implode(', ') }}</td>
                                        <td>{{ $prescription->medications->pluck('frequency')->implode(', ') }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $prescription->status === 'completed' ? 'success' : 'warning' 
                                            }}">
                                                {{ ucfirst($prescription->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer">
            @if($appointment->status === 'pending')
                <div class="btn-group" role="group">
                    <form action="{{ route('doctor.appointments.accept', $appointment->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Accept Appointment
                        </button>
                    </form>
                    <form action="{{ route('doctor.appointments.reject', $appointment->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i> Reject Appointment
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
