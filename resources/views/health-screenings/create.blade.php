@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Book New Health Screening</h3>
                </div>
                
                <form action="{{ route('health-screenings.booking.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="health_screening_id">Screening Type</label>
                            <select name="health_screening_id" id="health_screening_id" class="form-control @error('health_screening_id') is-invalid @enderror" required>
                                <option value="">Select Screening Type</option>
                                @foreach($screenings as $screening)
                                    <option value="{{ $screening->id }}" {{ old('health_screening_id') == $screening->id ? 'selected' : '' }}>
                                        {{ $screening->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('health_screening_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="clinic_id">Clinic</label>
                            <select name="clinic_id" id="clinic_id" class="form-control @error('clinic_id') is-invalid @enderror" required>
                                <option value="">Select Clinic</option>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}" {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                        {{ $clinic->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('clinic_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="appointment_date">Appointment Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" 
                                   class="form-control @error('appointment_date') is-invalid @enderror" 
                                   value="{{ old('appointment_date') }}" 
                                   min="{{ now()->format('Y-m-d') }}" 
                                   required>
                            @error('appointment_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="appointment_time">Appointment Time</label>
                            <input type="time" name="appointment_time" id="appointment_time" 
                                   class="form-control @error('appointment_time') is-invalid @enderror" 
                                   value="{{ old('appointment_time') }}" 
                                   required>
                            @error('appointment_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Book Health Screening
                        </button>
                        <a href="{{ route('health-screenings.booking.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
