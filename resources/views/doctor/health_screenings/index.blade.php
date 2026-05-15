@extends('layouts.doctor')

@section('content')
<div class="container">
    <h1 class="mb-4">Health Screening Bookings</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Health Screening Bookings</span>
            <a href="{{ route('doctor.health_screenings.reports') }}" class="btn btn-primary btn-sm">
                Generate Reports
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.health_screenings.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
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
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('doctor.health_screenings.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            @if($bookings->isEmpty())
                <p class="text-center">No health screening bookings found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Test</th>
                                <th>Clinic</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->test->name }}</td>
                                    <td>{{ $booking->clinic->name }}</td>
                                    <td>{{ $booking->appointment_date->format('d M Y') }}</td>
                                    <td>{{ $booking->appointment_time }}</td>
                                    <td>
                                        <span class="badge 
                                            @switch($booking->status)
                                                @case('pending') bg-warning @break
                                                @case('confirmed') bg-info @break
                                                @case('completed') bg-success @break
                                                @case('rejected') bg-danger @break
                                                @default bg-secondary
                                            @endswitch
                                        ">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('doctor.health_screenings.show', $booking->id) }}" 
                                               class="btn btn-sm btn-info">
                                                View
                                            </a>
                                            @if($booking->status == 'pending')
                                                <form action="{{ route('doctor.health_screenings.accept', $booking->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Accept
                                                    </button>
                                                </form>
                                                <form action="{{ route('doctor.health_screenings.reject', $booking->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return promptRejectionReason()">
                                                        Reject
                                                    </button>
                                                </form>
                                            @elseif($booking->status == 'confirmed')
                                                <form action="{{ route('doctor.health_screenings.complete', $booking->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Complete
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
                {{ $bookings->links() }}
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function promptRejectionReason() {
    const reason = prompt('Please provide a reason for rejecting the health screening booking:');
    if (reason) {
        const form = event.target.closest('form');
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'rejection_reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        return true;
    }
    return false;
}
</script>
@endpush
