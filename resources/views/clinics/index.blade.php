@extends('layouts.app')

@section('additional_styles')
<style>
    .clinic-section {
        padding: 50px 0;
    }
    
    .clinic-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .clinic-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .clinic-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
        transition: transform 0.3s ease;
    }
    
    .clinic-card:hover {
        transform: translateY(-5px);
    }
    
    .clinic-icon {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    
    .clinic-card .card-body {
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
    }
    
    .clinic-card .card-text {
        flex-grow: 1;
        margin-bottom: 1rem;
    }
    
    .clinic-table {
        margin-top: 50px;
    }
    
    .clinic-table th {
        background-color: var(--primary-color);
        color: white;
    }
    
    .appointment-modal .modal-header {
        background-color: var(--primary-color);
        color: white;
    }
    
    .appointment-modal .modal-content {
        border-radius: 10px;
    }
    
    .btn-custom {
        background-color: var(--primary-color);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-custom:hover {
        background-color: var(--secondary-color);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="clinic-section">
    <div class="container">
        <div class="clinic-header">
            <h2>Outpatient Clinics</h2>
            <p>Specialized healthcare facilities for your specific needs</p>
        </div>
        
        <!-- Clinics Cards -->
        <div class="row g-4" id="clinics-section">
            @foreach($clinics as $clinic)
                <div class="col-md-4 mb-4">
                    <div class="card clinic-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-hospital-alt clinic-icon me-2"></i>{{ $clinic->name }}
                            </h5>
                            <p class="card-text">
                                @if($clinic->description)
                                    {{ $clinic->description }}
                                @else
                                    Providing specialized healthcare services in {{ $clinic->department->name ?? 'General Medicine' }}
                                @endif
                            </p>
                            <button class="btn btn-custom mt-auto" onclick="openAppointmentModal('{{ $clinic->name }}', '{{ $clinic->id }}')">
                                Make an appointment
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Clinics Table -->
        <div class="clinic-table">
            <h3 class="mb-4">All Outpatient Clinics</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Clinic Name</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clinics as $clinic)
                            <tr>
                                <td>{{ $clinic->name }}</td>
                                <td>{{ $clinic->department->name ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-custom" onclick="openAppointmentModal('{{ $clinic->name }}', '{{ $clinic->id }}')">
                                        Make an Appointment
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade appointment-modal" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Make an Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="clinic_id" name="clinic_id">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Patient Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name ?? '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="doctor_id" class="form-label">Select Doctor</label>
                        <select class="form-select" id="doctor_id" name="doctor_id">
                            <option value="">Select a doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }} - {{ $doctor->specialization }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" min="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Time</label>
                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    
                    <h6>Clinic Working Days</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Open</th>
                                <th>Close</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Monday</td><td>08:00 AM</td><td>05:00 PM</td></tr>
                            <tr><td>Tuesday</td><td>08:00 AM</td><td>05:00 PM</td></tr>
                            <tr><td>Wednesday</td><td>08:00 AM</td><td>05:00 PM</td></tr>
                            <tr><td>Thursday</td><td>08:00 AM</td><td>05:00 PM</td></tr>
                            <tr><td>Friday</td><td>08:00 AM</td><td>05:00 PM</td></tr>
                        </tbody>
                    </table>
                    
                    <button type="submit" class="btn btn-success">Confirm Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAppointmentModal(clinicName, clinicId) {
        document.getElementById('appointmentModalLabel').textContent = `Make an Appointment - ${clinicName}`;
        document.getElementById('clinic_id').value = clinicId;
        
        const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
        modal.show();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const appointmentForm = document.getElementById('appointmentForm');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(event) {
                // Form validation can be added here
            });
        }
    });
</script>
@endsection
