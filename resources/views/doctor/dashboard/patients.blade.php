@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Patients</h4>
        <div class="btn-group">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Patients List</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPatientModal">
                    <i class="fas fa-plus me-1"></i> New Patient
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Last Visit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone }}</td>
                                <td>
                                    {{ $patient->appointments->last()?->appointment_date->format('Y-m-d') ?? 'Never' }}
                                </td>
                                <td>
                                    <a href="{{ route('doctor.appointments', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-calendar me-1"></i> Appointments
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- New Patient Modal -->
<div class="modal fade" id="newPatientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newPatientForm">
                    <!-- Form fields will be added here -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Create Patient</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add any patient-specific JavaScript here
    });
</script>
@endsection
