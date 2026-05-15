@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Dashboard</h4>
    </div>

    <!-- Quick Access Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-users me-2"></i>Total Users
                    </h5>
                    <p class="card-text h3">{{ $userCount }}</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-custom">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-md me-2"></i>Total Doctors
                    </h5>
                    <p class="card-text h3">{{ $doctorCount }}</p>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-custom">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-clinic-medical me-2"></i>Clinics
                    </h5>
                    <p class="card-text h3">{{ $clinicCount }}</p>
                    <a href="{{ route('admin.clinics.index') }}" class="btn btn-custom">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-hospital me-2"></i>Departments
                    </h5>
                    <p class="card-text h3">{{ $departmentCount }}</p>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-custom">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-check me-2"></i>Total Bookings
                    </h5>
                    <p class="card-text h3">{{ $totalBookingCount }}</p>
                    <div class="btn-group">
                        <a href="{{ route('admin.vaccinations.index') }}" class="btn btn-custom">
                            Vaccinations
                        </a>
                        <a href="{{ route('admin.health-screenings.index') }}" class="btn btn-custom">
                            Health Screenings
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-envelope me-2"></i>Messages
                    </h5>
                    <p class="card-text h3">{{ $messageCount }}</p>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-custom">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title mb-4">
                <i class="fas fa-users me-2"></i>Recent Users
                <a href="{{ route('admin.users.index') }}" class="btn btn-custom btn-sm float-end">View All</a>
            </h4>
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'badge-cancelled' : ($user->role === 'doctor' ? 'badge-confirmed' : ($user->role === 'staff' ? 'badge-pending' : 'badge-confirmed')) }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No recent users.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Messages Table -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">
                <i class="fas fa-envelope me-2"></i>Recent Messages
                <a href="{{ route('admin.messages.index') }}" class="btn btn-custom btn-sm float-end">View All</a>
            </h4>
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Replied By</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentMessages as $message)
                            <tr>
                                <td>{{ optional($message->user)->name ?? 'Unknown User' }}</td>
                                <td>{{ optional($message->repliedBy)->name ?? 'N/A' }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>
                                    @switch($message->status)
                                        @case('pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('in_progress')
                                            <span class="badge bg-info">In Progress</span>
                                            @break
                                        @case('resolved')
                                            <span class="badge bg-success">Resolved</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Unknown</span>
                                    @endswitch
                                </td>
                                <td>{{ $message->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">
                <i class="fas fa-calendar-check me-2"></i>Recent Bookings
                <div class="float-end">
                    <a href="{{ route('admin.vaccinations.index') }}" class="btn btn-custom btn-sm">Vaccinations</a>
                    <a href="{{ route('admin.health-screenings.index') }}" class="btn btn-custom btn-sm">Health Screenings</a>
                </div>
            </h4>
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Type</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBookings as $booking)
                            <tr>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>{{ $booking->booking_type }}</td>
                                <td>
                                    @if($booking->booking_type === 'Vaccination')
                                        {{ $booking->vaccination_type }}
                                    @else
                                        {{ $booking->screening_type }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge {{ $booking->status === 'pending' ? 'badge-pending' : ($booking->status === 'confirmed' ? 'badge-confirmed' : ($booking->status === 'completed' ? 'badge-confirmed' : 'badge-cancelled')) }}">
                                        {{ ucfirst($booking->status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No recent bookings.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Arial, sans-serif';
    Chart.defaults.global.defaultFontColor = '#1d3557';

    // Booking Chart
    var ctx = document.getElementById("bookingChart");
    if (ctx) {
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: JSON.parse('{!! json_encode($chartLabels) !!}'),
                datasets: [{
                    label: "Total Bookings",
                    lineTension: 0.3,
                    backgroundColor: "rgba(168, 218, 220, 0.05)",
                    borderColor: "#457b9d",
                    pointRadius: 3,
                    pointBackgroundColor: "#457b9d",
                    pointBorderColor: "#457b9d",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "#457b9d",
                    pointHoverBorderColor: "#457b9d",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: JSON.parse('{!! json_encode($chartData) !!}')
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 12
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            beginAtZero: true
                        },
                        gridLines: {
                            color: "#f1faee",
                            zeroLineColor: "#f1faee",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "#fff",
                    bodyFontColor: "#1d3557",
                    titleMarginBottom: 10,
                    titleFontColor: '#1d3557',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10
                }
            }
        });
    }
</script>
@endsection