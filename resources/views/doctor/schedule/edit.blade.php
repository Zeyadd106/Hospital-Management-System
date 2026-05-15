@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit Schedule</h4>
        <div class="btn-group">
            <a href="{{ route('doctor.schedule') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Schedule
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('doctor.schedule.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Day of Week</label>
                    <select name="day_of_week" class="form-select" required>
                        @foreach(DoctorSchedule::daysOfWeek() as $day)
                            <option value="{{ $day }}" {{ $schedule->day_of_week === $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Time</label>
                    <input type="time" name="start_time" class="form-control" 
                           value="{{ $schedule->start_time->format('H:i') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Time</label>
                    <input type="time" name="end_time" class="form-control" 
                           value="{{ $schedule->end_time->format('H:i') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ $schedule->status === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ $schedule->status === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Schedule</button>
                </div>
            </form>
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
