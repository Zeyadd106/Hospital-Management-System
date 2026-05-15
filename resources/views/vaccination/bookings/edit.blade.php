@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Vaccination Booking') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('vaccination.booking.update', $booking->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control @error('appointment_date') is-invalid @enderror" name="appointment_date" value="{{ old('appointment_date', $booking->appointment_date) }}" required autocomplete="date">

                                @error('appointment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="time" class="col-md-4 col-form-label text-md-right">{{ __('Time') }}</label>

                            <div class="col-md-6">
                                <input id="time" type="time" class="form-control @error('appointment_time') is-invalid @enderror" name="appointment_time" value="{{ old('appointment_time', $booking->appointment_time) }}" required>

                                @error('appointment_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="vaccine_id" class="col-md-4 col-form-label text-md-right">{{ __('Vaccine Type') }}</label>

                            <div class="col-md-6">
                                <select id="vaccine_id" class="form-control @error('vaccine_id') is-invalid @enderror" name="vaccine_id" required>
                                    <option value="">Select Vaccine Type</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}" {{ old('vaccine_id', $booking->vaccine_id) == $vaccine->id ? 'selected' : '' }}>
                                            {{ $vaccine->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('vaccine_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="clinic_id" class="col-md-4 col-form-label text-md-right">{{ __('Clinic') }}</label>

                            <div class="col-md-6">
                                <select id="clinic_id" class="form-control @error('clinic_id') is-invalid @enderror" name="clinic_id" required>
                                    <option value="">Select Clinic</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{ $clinic->id }}" {{ old('clinic_id', $booking->clinic_id) == $clinic->id ? 'selected' : '' }}>
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
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Booking') }}
                                </button>
                                <a href="{{ route('vaccination.booking.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection