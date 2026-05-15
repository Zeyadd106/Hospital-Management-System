@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary-bg: #f8f9fa;
        --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        --card-hover: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
        --border-radius: 0.5rem;
        --transition: all 0.3s ease;
    }

    body {
        background-color: var(--primary-bg);
    }

    .dashboard-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .stat-card {
        border: none;
        border-radius: var(--border-radius);
        transition: var(--transition);
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover);
    }

    .stat-card .card-body {
        position: relative;
        z-index: 1;
    }

    .stat-card .icon-bg {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
    }

    .card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        margin-bottom: 1.5rem;
    }

    .card:hover {
        box-shadow: var(--card-hover);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
    }

    .activity-item {
        position: relative;
        padding: 1rem 1.5rem 1rem 3rem;
        border-left: 3px solid #e9ecef;
        transition: var(--transition);
    }

    .activity-item:hover {
        background-color: #f8f9fa;
        border-left-color: #4e73df;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        width: 12px;
        height: 12px;
        left: -7.5px;
        background-color: #4e73df;
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
    }

    .activity-time {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}" 
                           href="{{ route('pharmacy.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('pharmacy.medications.*') ? 'active' : '' }}" 
                           href="{{ route('pharmacy.medications.index') }}">
                            <i class="fas fa-pills me-2"></i>
                            All Medications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('pharmacy.medications.create') ? 'active' : '' }}" 
                           href="{{ route('pharmacy.medications.create') }}">
                            <i class="fas fa-plus-circle me-2"></i>
                            Add New Medication
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('pharmacy.reports.*') ? 'active' : '' }}" 
                           href="{{ route('pharmacy.reports.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>
                            Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('pharmacy.prescription-refills.*') ? 'active' : '' }}" 
                           href="{{ route('pharmacy.prescription-refills.index') }}">
                            <i class="fas fa-prescription-bottle-alt me-2"></i>
                            Prescription Refills
                            @if($pendingRefills > 0)
                                <span class="badge bg-danger rounded-pill ms-2">{{ $pendingRefills }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="dashboard-header d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="mb-0">Here's what's happening with your pharmacy today.</p>
                </div>
                <div class="d-flex">
                    <a href="{{ route('pharmacy.medications.create') }}" class="btn btn-light me-2">
                        <i class="fas fa-plus me-1"></i> Add Medication
                    </a>
                    <a href="{{ route('pharmacy.medications.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-list me-1"></i> View All
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card card border-left-primary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Medications</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMedications }}</div>
                                </div>
                                <i class="fas fa-pills icon-bg"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-primary stretched-link" href="{{ route('pharmacy.medications.index') }}">View Details</a>
                            <div class="small text-primary"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card card border-left-success h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Stock Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStock }}</div>
                                </div>
                                <i class="fas fa-boxes icon-bg"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-success stretched-link" href="{{ route('pharmacy.reports.stock') }}">View Report</a>
                            <div class="small text-success"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card card border-left-warning h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Refills</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingRefills }}</div>
                                </div>
                                <i class="fas fa-prescription-bottle-alt icon-bg"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-warning stretched-link" href="{{ route('pharmacy.prescription-refills.index') }}">Manage Refills</a>
                            <div class="small text-warning"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card card border-left-info h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Low Stock Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockCount ?? 0 }}</div>
                                </div>
                                <i class="fas fa-exclamation-triangle icon-bg"></i>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-info stretched-link" href="{{ route('pharmacy.reports.stock') }}">View Items</a>
                            <div class="small text-info"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Activities -->
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Recent Activities</h6>
                            <a href="#" class="btn btn-sm btn-link">View All</a>
                        </div>
                        <div class="card-body p-0">
                            @if($recentActivities->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentActivities as $activity)
                                        <div class="activity-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $activity->title }}</h6>
                                                <small class="activity-time">{{ $activity->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ $activity->description }}</p>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $activity->status === 'completed' ? 'success' : ($activity->status === 'pending' ? 'warning' : 'danger') }} me-2">
                                                    {{ ucfirst($activity->status) }}
                                                </span>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i> {{ $activity->user->name ?? 'System' }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="far fa-calendar-alt"></i>
                                    <h5>No recent activities</h5>
                                    <p class="mb-0">Activities will appear here as they happen</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Low Stock Items -->
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Low Stock Alerts</h6>
                            <span class="badge bg-danger">{{ $lowStockItems->count() }}</span>
                        </div>
                        <div class="card-body p-0">
                            @if(isset($lowStockItems) && $lowStockItems->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <tbody>
                                            @foreach($lowStockItems as $item)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-1">
                                                            <a href="{{ route('pharmacy.medications.show', $item->medication_id) }}">
                                                                {{ $item->medication->name }}
                                                            </a>
                                                        </h6>
                                                        <div class="progress" style="height: 5px;">
                                                            @php
                                                                $percentage = ($item->current_stock / $item->min_stock) * 100;
                                                                $percentage = min(100, max(0, $percentage));
                                                            @endphp
                                                            <div class="progress-bar bg-{{ $item->current_stock <= 0 ? 'danger' : 'warning' }}" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $percentage }}%" 
                                                                 aria-valuenow="{{ $percentage }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ $item->current_stock }} of {{ $item->min_stock * 2 }} remaining</small>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('pharmacy.medications.edit', $item->medication_id) }}" 
                                                           class="btn btn-sm btn-outline-primary btn-action" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Restock">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <h5>All items are well stocked!</h5>
                                    <p class="mb-0">No low stock alerts at this time</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('pharmacy.medications.create') }}" class="btn btn-outline-primary w-100 h-100 py-3 text-center">
                                        <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                                        <span>Add Medication</span>
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('pharmacy.medications.index') }}" class="btn btn-outline-success w-100 h-100 py-3 text-center">
                                        <i class="fas fa-pills fa-2x d-block mb-2"></i>
                                        <span>Manage Medications</span>
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('pharmacy.prescription-refills.index') }}" class="btn btn-outline-warning w-100 h-100 py-3 text-center position-relative">
                                        <i class="fas fa-prescription-bottle-alt fa-2x d-block mb-2"></i>
                                        <span>Process Refills</span>
                                        @if($pendingRefills > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $pendingRefills }}
                                            </span>
                                        @endif
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('pharmacy.reports.index') }}" class="btn btn-outline-info w-100 h-100 py-3 text-center">
                                        <i class="fas fa-chart-bar fa-2x d-block mb-2"></i>
                                        <span>View Reports</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-hide alerts after 5 seconds
        var alertList = document.querySelectorAll('.alert');
        alertList.forEach(function(alert) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endpush
@endsection
