@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create New Vaccination Booking</h3>
                </div>
                
                <form action="{{ route('admin.vaccinations.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">Patient</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">Select Patient</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="vaccination_type">Vaccination Type</label>
                            <select name="vaccination_type" id="vaccination_type" class="form-control @error('vaccination_type') is-invalid @enderror" required>
                                <option value="">Select Vaccination Type</option>
                                @foreach($vaccines as $vaccine)
                                    <option value="{{ $vaccine->name }}" {{ old('vaccination_type') == $vaccine->name ? 'selected' : '' }}>
                                        {{ $vaccine->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vaccination_type')
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
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Vaccination Booking
                        </button>
                        <a href="{{ route('admin.vaccinations.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
