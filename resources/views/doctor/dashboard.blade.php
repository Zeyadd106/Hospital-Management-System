@extends('layouts.app')

@section('styles')
<style>
    .dashboard-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-left: 4px solid;
    }
    .dashboard-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .quick-stats-icon {
        font-size: 2.5rem;
        opacity: 0.7;
        position: absolute;
        top: 15px;
        right: 15px;
    }
    .card-progress {
        height: 5px;
        background-color: rgba(0,0,0,0.1);
        margin-top: 15px;
    }
    .card-progress-bar {
        height: 100%;
        background-color: var(--primary);
    }
    .recent-activity-card {
        max-height: 500px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Doctor Dashboard</h2>
            <p class="text-muted">Welcome back, Dr. {{ auth()->user()->name }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('doctor.profile.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-user-edit me-1"></i> Edit Profile
            </a>
            <a href="{{ route('doctor.availability.index') }}" class="btn btn-outline-success">
                <i class="fas fa-clock me-1"></i> Manage Availability
            </a>
            <a href="{{ route('doctor.appointments.today') }}" class="btn btn-outline-info">
                <i class="fas fa-calendar-day me-1"></i> Today's Schedule
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        @foreach($quickStats as $stat)
            <div class="col-md-3">
                <div class="card dashboard-card border-{{ $stat['color'] }} h-100 position-relative shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-{{ $stat['icon'] }} me-2"></i>{{ $stat['title'] }}
                        </h5>
                        <p class="card-text display-6 fw-bold text-{{ $stat['color'] }}">{{ $stat['count'] }}</p>
                        <a href="{{ route($stat['route']) }}" class="btn btn-{{ $stat['color'] }} w-100 mt-2">
                            View Details <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <div class="card-progress">
                            <div class="card-progress-bar" style="width: {{ min(($stat['count'] / 10) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Messaging Card -->
        <div class="col-md-3">
            <div class="card dashboard-card border-danger h-100 position-relative shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-envelope me-2"></i>New Messages
                    </h5>
                    <p class="card-text display-6 fw-bold text-danger">{{ $stats['newMessages'] }}</p>
                    <a href="{{ route('doctor.messages.index') }}" class="btn btn-danger w-100 mt-2">
                        View Messages <i class="fas fa-comments ms-2"></i>
                    </a>
                    <div class="card-progress">
                        <div class="card-progress-bar bg-danger" style="width: {{ min(($stats['newMessages'] / 5) * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4 mb-4">
        <!-- Recent Prescriptions -->
        <div class="col-md-6">
            <div class="card recent-activity-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-prescription me-2 text-primary"></i>Recent Prescriptions
                    </h5>
                    <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentPrescriptions->isEmpty())
                        <div class="text-center p-4">
                            <i class="fas fa-file-medical-alt text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No recent prescriptions.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Medications</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPrescriptions as $prescription)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $prescription->patient->avatar ?? asset('images/default-avatar.png') }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 35px; height: 35px; object-fit: cover;">
                                                    {{ $prescription->patient->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 150px;">
                                                    {{ $prescription->medications->pluck('name')->implode(', ') }}
                                                </div>
                                            </td>
                                            <td>{{ $prescription->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $prescription->status === 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($prescription->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Health Screenings -->
        <div class="col-md-6">
            <div class="card recent-activity-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-heartbeat me-2 text-danger"></i>Recent Health Screenings
                    </h5>
                    <a href="{{ route('doctor.health-screenings.index') }}" class="btn btn-sm btn-outline-danger">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentScreenings->isEmpty())
                        <div class="text-center p-4">
                            <i class="fas fa-chart-line text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No recent screenings.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Test</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentScreenings as $screening)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $screening->patient->avatar ?? asset('images/default-avatar.png') }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 35px; height: 35px; object-fit: cover;">
                                                    {{ $screening->patient->name }}
                                                </div>
                                            </td>
                                            <td>{{ $screening->test->name }}</td>
                                            <td>{{ $screening->date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $screening->status === 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($screening->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-calendar-alt me-2 text-success"></i>Upcoming Appointments
            </h5>
            <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-outline-success">
                View All
            </a>
        </div>
        <div class="card-body">
            @if($appointments->isEmpty())
                <div class="text-center p-4">
                    <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No upcoming appointments scheduled.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Clinic</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $appointment->patient->avatar ?? asset('images/default-avatar.png') }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 35px; height: 35px; object-fit: cover;">
                                            {{ $appointment->patient->name }}
                                        </div>
                                    </td>
                                    <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                    <td>{{ $appointment->appointment_time }}</td>
                                    <td>{{ $appointment->clinic->name }}</td>
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
                                                <form action="{{ route('doctor.appointments.accept', $appointment->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('doctor.appointments.reject', $appointment->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        dashboardCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endsection
