@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-center mb-4">Book Health Screening</h2>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="{{ $healthScreening->icon ?? 'fas fa-heartbeat' }} text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-1">{{ $healthScreening->name }}</h3>
                                <p class="text-muted mb-0">{{ ucfirst($healthScreening->category) }} screening</p>
                            </div>
                        </div>
                        <p>{{ $healthScreening->description }}</p>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="d-block"><strong>Duration:</strong> {{ $healthScreening->duration }} minutes</small>
                                <small class="d-block"><strong>Recommended For:</strong> {{ ucfirst($healthScreening->recommended_age_group) }}</small>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Price:</strong> ${{ number_format($healthScreening->price, 2) }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('health-screenings.booking.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="screening_id" value="{{ $healthScreening->id }}">
                        
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
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="booking_date" class="form-label">Appointment Date</label>
                                <select class="form-select @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" required>
                                    <option value="">Select a date</option>
                                    @foreach(array_keys($availableDates) as $date)
                                        <option value="{{ $date }}" {{ old('booking_date') == $date ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="booking_time" class="form-label">Time Slot</label>
                                <select class="form-select @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" required>
                                    <option value="">Select a time</option>
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot['value'] }}" {{ old('booking_time') == $slot['value'] ? 'selected' : '' }}>
                                            {{ $slot['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('booking_time')
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
                            <a href="{{ route('health-screenings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

