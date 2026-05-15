@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">All Appointments</h2>
        <div class="btn-group">
            <a href="{{ route('doctor.appointments.today') }}" class="btn btn-outline-primary">
                <i class="fas fa-calendar-day me-1"></i> Today's Appointments
            </a>
            <a href="{{ route('doctor.appointments.upcoming') }}" class="btn btn-outline-success">
                <i class="fas fa-calendar-alt me-1"></i> Upcoming
            </a>
            <a href="{{ route('doctor.appointments.past') }}" class="btn btn-outline-secondary">
                <i class="fas fa-history me-1"></i> Past Appointments
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Clinic</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $appointment->patient->avatar ?? asset('images/default-avatar.png') }}" 
                                             class="rounded-circle me-2" 
                                             style="width: 35px; height: 35px; object-fit: cover;">
                                        <a href="{{ route('doctor.patients.profile', $appointment->patient->id) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ $appointment->patient->name }}
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $appointment->clinic->name }}</td>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>{{ $appointment->appointment_time }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $appointment->status === 'confirmed' ? 'success' : 
                                        ($appointment->status === 'pending' ? 'warning' : 'secondary') 
                                    }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('doctor.appointments.show', $appointment->id) }}" 
                                           class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($appointment->status === 'pending')
                                            <form action="{{ route('doctor.appointments.accept', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Accept Appointment">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Rejection Modal Button -->
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $appointment->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            
                                            <!-- Rejection Modal -->
                                            <div class="modal fade" id="rejectModal{{ $appointment->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Appointment</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('doctor.appointments.reject', $appointment->id) }}" method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                                                                    <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" placeholder="Please provide a reason for rejecting this appointment..."></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Reject Appointment</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-4">
                                    <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-3">No appointments found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($appointments->hasPages())
            <div class="card-footer">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
