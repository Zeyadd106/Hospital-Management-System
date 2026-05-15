@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Today's Appointments</h2>
        <div class="btn-group">
            <a href="{{ route('doctor.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-1"></i> All Appointments
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Clinic</th>
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
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4">
                                    <i class="fas fa-calendar-day text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-3">No appointments today.</p>
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

@section('styles')
<style>
    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
    }
</style>
@endsection
