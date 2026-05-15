@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-center mb-4">Book Vaccination Appointment</h2>
                    
                    @if($vaccine)
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fas fa-syringe text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-1">{{ $vaccine->name }}</h3>
                                <p class="text-muted mb-0">{{ $vaccine->manufacturer }}</p>
                            </div>
                        </div>
                        <p>{{ $vaccine->description }}</p>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="d-block"><strong>Recommended Age:</strong> {{ $vaccine->recommended_age }}</small>
                                <small class="d-block"><strong>Doses Required:</strong> {{ $vaccine->doses_required }}</small>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Price:</strong> 
                                    @if($vaccine->price == 0)
                                        Free
                                    @else
                                        ${{ number_format($vaccine->price, 2) }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <hr class="my-4">
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('vaccinations.booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="vaccine_id" value="{{ $vaccine->id }}">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="appointment_date" class="form-label">Appointment Date</label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                       id="appointment_date" name="appointment_date" 
                                       value="{{ old('appointment_date') }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="appointment_time" class="form-label">Appointment Time</label>
                                <select name="appointment_time" id="appointment_time" class="form-select @error('appointment_time') is-invalid @enderror" required>
                                    <option value="">Select Time</option>
                                    @foreach($timeSlots as $time)
                                        <option value="{{ $time }}" {{ old('appointment_time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                    @endforeach
                                </select>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="form-label">Additional Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Confirm Booking</button>
                            <a href="{{ route('vaccinations.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
