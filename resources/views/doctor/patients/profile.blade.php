@extends('layouts.doctor')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Patient Profile: {{ $patient->name }}</h2>
        <a href="{{ route('doctor.patients.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>

    <div class="row">
        <!-- Patient Information -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ $patient->avatar ? asset('storage/' . $patient->avatar) : asset('images/default-avatar.png') }}" 
                             class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <h4>{{ $patient->name }}</h4>
                    <p class="text-muted">Patient</p>
                    
                    <div class="mt-4 text-start">
                        <p><strong><i class="fas fa-envelope me-2"></i> Email:</strong> {{ $patient->email }}</p>
                        <p><strong><i class="fas fa-phone me-2"></i> Phone:</strong> {{ $patient->phone ?? 'N/A' }}</p>
                        <p><strong><i class="fas fa-birthday-cake me-2"></i> Date of Birth:</strong> 
                            {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                        <p><strong><i class="fas fa-venus-mars me-2"></i> Gender:</strong> {{ $patient->gender ?? 'N/A' }}</p>
                        <p><strong><i class="fas fa-map-marker-alt me-2"></i> Address:</strong> {{ $patient->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            @if($patient->patientProfile)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Medical Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Blood Type:</strong> {{ $patient->patientProfile->blood_type ?? 'N/A' }}</p>
                    <p><strong>Height:</strong> {{ $patient->patientProfile->height ?? 'N/A' }} cm</p>
                    <p><strong>Weight:</strong> {{ $patient->patientProfile->weight ?? 'N/A' }} kg</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-8">
            <!-- Upcoming Appointments -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Appointments</h5>
                    <a href="{{ route('doctor.appointments.index', ['status' => 'upcoming']) }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($upcomingAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Clinic</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>
                                            {{ $appointment->appointment_date->format('M d, Y') }}
                                            <div class="text-muted small">{{ $appointment->appointment_time->format('h:i A') }}</div>
                                        </td>
                                        <td>{{ $appointment->clinic->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times text-muted" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No upcoming appointments</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Prescriptions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Prescriptions</h5>
                    <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($prescriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Medications</th>
                                        <th>Instructions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptions as $prescription)
                                    <tr>
                                        <td>{{ $prescription->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @foreach($prescription->medications as $medication)
                                                <span class="badge bg-primary me-1">{{ $medication->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ Str::limit($prescription->instructions, 50) }}</td>
                                        <td>
                                            <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-prescription text-muted" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No prescriptions found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
