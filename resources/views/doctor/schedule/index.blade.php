@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Schedule</h4>
        <div class="btn-group">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newScheduleModal">
                <i class="fas fa-plus me-1"></i> Add Schedule
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->day_of_week }}</td>
                                <td>{{ $schedule->start_time->format('H:i') }}</td>
                                <td>{{ $schedule->end_time->format('H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $schedule->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('doctor.schedule.edit', $schedule->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('doctor.schedule.destroy', $schedule->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- New Schedule Modal -->
<div class="modal fade" id="newScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('doctor.schedule.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Day of Week</label>
                        <select name="day_of_week" class="form-select" required>
                            @foreach($schedules->groupBy('day_of_week')->keys() as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add any schedule-specific JavaScript here
    });
</script>
@endsection
