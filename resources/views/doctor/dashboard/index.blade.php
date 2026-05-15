@extends('layouts.doctor')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Doctor Dashboard</h4>
        <div>
            <span class="badge bg-primary">{{ now()->format('l, F d, Y') }}</span>
        </div>
    </div>

    <!-- Quick Access Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>Today's Appointments
                    </h5>
                    <p class="card-text h3">{{ $todayAppointments }}</p>
                    <a href="{{ route('doctor.appointments.today') }}" class="btn btn-primary w-100">
                        <i class="fas fa-eye me-1"></i> View Schedule
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-stethoscope me-2 text-success"></i>Pending Screenings
                    </h5>
                    <p class="card-text h3">{{ $pendingScreenings }}</p>
                    <a href="{{ route('doctor.screenings.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-eye me-1"></i> View Screenings
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-syringe me-2 text-warning"></i>Pending Vaccinations
                    </h5>
                    <p class="card-text h3">{{ $pendingVaccinations }}</p>
                    <a href="{{ route('doctor.vaccinations.index') }}" class="btn btn-warning w-100">
                        <i class="fas fa-eye me-1"></i> View Vaccinations
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-envelope me-2 text-danger"></i>Unread Messages
                    </h5>
                    <p class="card-text h3">{{ $unreadMessages }}</p>
                    <a href="{{ route('doctor.messages.index') }}" class="btn btn-danger w-100">
                        <i class="fas fa-envelope-open me-1"></i> View Messages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Recent Patients -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-injured me-2"></i>Recent Patients
                    </h5>
                    <a href="{{ route('doctor.patients.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Patient
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patient</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Last Visit</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPatients as $patient)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ $patient->avatar ?? asset('img/default-avatar.jpg') }}" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $patient->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $patient->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $patient->last_visit ? $patient->last_visit->format('M d, Y') : 'N/A' }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-{{ $patient->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($patient->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-link text-info me-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('doctor.patients.edit', $patient->id) }}" class="btn btn-link text-warning me-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('doctor.prescriptions.create', ['patient_id' => $patient->id]) }}" class="btn btn-link text-success">
                                                    <i class="fas fa-prescription"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-sm mb-0">No patients found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Appointments
                    </h5>
                    <div>
                        <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list me-1"></i> All Appointments
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patient</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date & Time</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ $appointment->user->avatar ?? asset('img/default-avatar.jpg') }}" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $appointment->user->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $appointment->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $appointment->appointment_date->format('h:i A') }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-{{ $appointment->status == 'pending' ? 'warning' : ($appointment->status == 'completed' ? 'success' : ($appointment->status == 'approved' ? 'info' : 'danger')) }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if($appointment->status == 'pending')
                                                    <form action="{{ route('doctor.appointments.accept', $appointment->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Accept">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('doctor.appointments.reject', $appointment->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($appointment->status == 'approved')
                                                    <form action="{{ route('doctor.appointments.complete', $appointment->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Mark as Completed">
                                                            <i class="fas fa-check-double"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <p class="text-sm mb-0">No upcoming appointments</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Health Screenings -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-stethoscope me-2"></i>Recent Health Screenings
                    </h5>
                    <div>
                        <a href="{{ route('doctor.screenings.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list me-1"></i> All Screenings
                        </a>
                        <a href="{{ route('doctor.screenings.reports') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-chart-bar me-1"></i> Reports
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patient</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Test</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentScreenings as $screening)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ $screening->user->avatar ?? asset('img/default-avatar.jpg') }}" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $screening->user->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $screening->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $screening->test->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $screening->appointment_date->format('M d, Y') }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $screening->appointment_date->format('h:i A') }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-{{ $screening->status == 'pending' ? 'warning' : ($screening->status == 'completed' ? 'success' : ($screening->status == 'approved' ? 'info' : 'danger')) }}">
                                                {{ ucfirst($screening->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if($screening->status == 'pending')
                                                    <form action="{{ route('doctor.screenings.approve', $screening->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('doctor.screenings.reject', $screening->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('doctor.screenings.show', $screening->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-sm mb-0">No recent health screenings</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection