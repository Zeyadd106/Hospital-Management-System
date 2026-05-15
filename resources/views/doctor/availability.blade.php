@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Availability</h2>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('doctor.availability.update') }}" method="POST">
                @csrf
                <div class="row">
                    @foreach(range(0, 6) as $day)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    {{ $days[$day] }}
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" 
                                               id="day{{ $day }}" 
                                               name="schedules[{{ $day }}][is_available]"
                                               value="1"
                                               {{ $schedules->where('day_of_week', $day)->first() ? 'checked' : '' }}
                                               onchange="toggleTimeInputs(this, {{ $day }})">
                                        <label class="form-check-label" for="day{{ $day }}">
                                            Available
                                        </label>
                                    </div>

                                    <div class="time-inputs" id="timeInputs{{ $day }}" 
                                         style="display: {{ $schedules->where('day_of_week', $day)->first() ? 'block' : 'none' }};">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="start_time{{ $day }}" class="form-label">Start Time</label>
                                                <input type="time" class="form-control" 
                                                       id="start_time{{ $day }}" 
                                                       name="schedules[{{ $day }}][start_time]"
                                                       value="{{ $schedules->where('day_of_week', $day)->first()?->start_time ?? '' }}"
                                                       required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="end_time{{ $day }}" class="form-label">End Time</label>
                                                <input type="time" class="form-control" 
                                                       id="end_time{{ $day }}" 
                                                       name="schedules[{{ $day }}][end_time]"
                                                       value="{{ $schedules->where('day_of_week', $day)->first()?->end_time ?? '' }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    function toggleTimeInputs(checkbox, day) {
        const timeInputs = document.getElementById(`timeInputs${day}`);
        timeInputs.style.display = checkbox.checked ? 'block' : 'none';
        
        // Clear time inputs when unchecked
        if (!checkbox.checked) {
            document.getElementById(`start_time${day}`).value = '';
            document.getElementById(`end_time${day}`).value = '';
        }
    }

    // Initialize checkboxes for days that have schedules
    @foreach($schedules as $schedule)
        toggleTimeInputs(document.getElementById('day{{ $schedule->day_of_week }}'), {{ $schedule->day_of_week }});
    @endforeach
</script>
@endsection
