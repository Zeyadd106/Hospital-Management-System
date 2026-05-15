@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Appointments</h4>
        <div class="btn-group">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Appointments List</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                    <i class="fas fa-plus me-1"></i> New Appointment
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->patient->name }}</td>
                                <td>{{ $appointment->appointment_date->format('Y-m-d') }}</td>
                                <td>{{ $appointment->appointment_time }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($appointment->status === 'pending')
                                        <button class="btn btn-sm btn-success accept-btn" data-id="{{ $appointment->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger reject-btn" data-id="{{ $appointment->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- New Appointment Modal -->
<div class="modal fade" id="newAppointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newAppointmentForm">
                    <!-- Form fields will be added here -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Create Appointment</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle appointment actions
        $('.accept-btn').click(function() {
            const appointmentId = $(this).data('id');
            // Add AJAX call to accept appointment
        });

        $('.reject-btn').click(function() {
            const appointmentId = $(this).data('id');
            // Add AJAX call to reject appointment
        });
    });
</script>
@endsection
