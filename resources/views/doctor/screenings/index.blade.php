@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Health Screenings</h2>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <h5 class="mb-0">
                <i class="fas fa-heartbeat me-2 text-danger"></i>Health Screening Bookings
            </h5>
            <a href="{{ route('doctor.screenings.reports') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-chart-bar me-1"></i> Generate Reports
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.screenings.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}" placeholder="Start Date">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date') }}" placeholder="End Date">
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('doctor.screenings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Test</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($screenings as $screening)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $screening->user->avatar ?? asset('images/default-avatar.png') }}" 
                                             class="rounded-circle me-2" 
                                             style="width: 35px; height: 35px; object-fit: cover;">
                                        {{ $screening->user->name }}
                                    </div>
                                </td>
                                <td>{{ $screening->test->name }}</td>
                                <td>{{ $screening->date->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $screening->status === 'completed' ? 'success' : 
                                        ($screening->status === 'pending' ? 'warning' : 
                                        ($screening->status === 'approved' ? 'info' : 'danger')) 
                                    }}">
                                        {{ ucfirst($screening->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('doctor.screenings.show', $screening->id) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($screening->status === 'pending')
                                            <form action="{{ route('doctor.screenings.approve', $screening->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('doctor.screenings.reject', $screening->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject this screening?')">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-file-medical-alt text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-3">No health screenings found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $screenings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
