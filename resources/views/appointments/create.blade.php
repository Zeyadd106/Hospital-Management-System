@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Book an Appointment') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="doctor_id" class="col-md-4 col-form-label text-md-right">{{ __('Doctor') }}</label>
                            <div class="col-md-6">
                                <select id="doctor_id" class="form-control @error('doctor_id') is-invalid @enderror" name="doctor_id" required>
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }} ({{ $doctor->specialization }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('doctor_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="appointment_date" class="col-md-4 col-form-label text-md-right">{{ __('Appointment Date') }}</label>
                            <div class="col-md-6">
                                <input id="appointment_date" type="date" 
                                       class="form-control @error('appointment_date') is-invalid @enderror" 
                                       name="appointment_date" 
                                       value="{{ old('appointment_date') }}" 
                                       required 
                                       min="{{ now()->format('Y-m-d') }}">

                                @error('appointment_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="appointment_time" class="col-md-4 col-form-label text-md-right">{{ __('Appointment Time') }}</label>
                            <div class="col-md-6">
                                <input id="appointment_time" type="time" 
                                       class="form-control @error('appointment_time') is-invalid @enderror" 
                                       name="appointment_time" 
                                       value="{{ old('appointment_time') }}" 
                                       required>

                                @error('appointment_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Appointment Type') }}</label>
                            <div class="col-md-6">
                                <select id="type" class="form-control @error('type') is-invalid @enderror" name="type" required>
                                    <option value="">Select Appointment Type</option>
                                    <option value="regular" {{ old('type') == 'regular' ? 'selected' : '' }}>Regular Consultation</option>
                                    <option value="follow_up" {{ old('type') == 'follow_up' ? 'selected' : '' }}>Follow-up Consultation</option>
                                    <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Emergency Consultation</option>
                                </select>

                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Clinic Selection -->
                        <div class="form-group row mb-3">
                            <label for="clinic_id" class="col-md-4 col-form-label text-md-right">{{ __('Clinic') }}</label>
                            <div class="col-md-6">
                                <select id="clinic_id" class="form-control @error('clinic_id') is-invalid @enderror" name="clinic_id" required>
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
                        </div>

                        <!-- Purpose -->
                        <div class="form-group row mb-3">
                            <label for="purpose" class="col-md-4 col-form-label text-md-right">{{ __('Purpose of Visit') }}</label>
                            <div class="col-md-6">
                                <textarea id="purpose" 
                                          class="form-control @error('purpose') is-invalid @enderror" 
                                          name="purpose" 
                                          rows="3" 
                                          required
                                          placeholder="Please describe the reason for your visit...">{{ old('purpose') }}</textarea>

                                @error('purpose')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="reason" class="col-md-4 col-form-label text-md-right">{{ __('Reason for Consultation') }}</label>
                            <div class="col-md-6">
                                <textarea id="reason" 
                                          class="form-control @error('reason') is-invalid @enderror" 
                                          name="reason" 
                                          rows="3" 
                                          required>{{ old('reason') }}</textarea>

                                @error('reason')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="notes" class="col-md-4 col-form-label text-md-right">{{ __('Additional Notes (Optional)') }}</label>
                            <div class="col-md-6">
                                <textarea id="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          name="notes" 
                                          rows="2">{{ old('notes') }}</textarea>

                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Book Appointment') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
