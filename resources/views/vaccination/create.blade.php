@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Book Vaccination') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('vaccination.book.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="vaccine_id" class="col-md-4 col-form-label text-md-right">{{ __('Vaccine') }}</label>
                            <div class="col-md-6">
                                <select id="vaccine_id" class="form-control @error('vaccine_id') is-invalid @enderror" name="vaccine_id" required>
                                    <option value="">Select Vaccine</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}">{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="clinic_id" class="col-md-4 col-form-label text-md-right">{{ __('Clinic') }}</label>
                            <div class="col-md-6">
                                <select id="clinic_id" class="form-control @error('clinic_id') is-invalid @enderror" name="clinic_id" required>
                                    <option value="">Select Clinic</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="appointment_date" class="col-md-4 col-form-label text-md-right">{{ __('Appointment Date') }}</label>
                            <div class="col-md-6">
                                <input id="appointment_date" type="date" class="form-control @error('appointment_date') is-invalid @enderror" name="appointment_date" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="appointment_time" class="col-md-4 col-form-label text-md-right">{{ __('Appointment Time') }}</label>
                            <div class="col-md-6">
                                <input id="appointment_time" type="time" class="form-control @error('appointment_time') is-invalid @enderror" name="appointment_time" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Book Vaccination') }}
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
