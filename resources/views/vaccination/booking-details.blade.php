@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Vaccination Appointment Details</h2>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="badge bg-{{ $booking->status_badge }} p-2 fs-6">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Confirmation Code</h5>
                            <p class="fs-5 fw-bold">{{ $booking->confirmation_code }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Vaccine</h5>
                            <p>{{ $booking->vaccine->name }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Date</h5>
                            <p>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('l, F j, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Time</h5>
                            <p>{{ $booking->time_slot }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Name</h5>
                            <p>{{ $booking->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Email</h5>
                            <p>{{ $booking->email }}</p>
                        </div>
                    </div>
                    
                    @if($booking->phone)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Phone</h5>
                            <p>{{ $booking->phone }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($booking->notes)
                    <div class="mb-4">
                        <h5>Additional Notes</h5>
                        <p>{{ $booking->notes }}</p>
                    </div>
                    @endif
                    
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">Vaccine Information</h5>
                        <p class="mb-2">{{ $booking->vaccine->description }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="d-block"><strong>Manufacturer:</strong> {{ $booking->vaccine->manufacturer }}</small>
                                <small class="d-block"><strong>Recommended Age:</strong> {{ $booking->vaccine->recommended_age }}</small>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Doses Required:</strong> {{ $booking->vaccine->doses_required }}</small>
                                <small class="d-block"><strong>Price:</strong> 
                                    @if($booking->vaccine->price == 0)
                                        Free
                                    @else
                                        ${{ number_format($booking->vaccine->price, 2) }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('vaccinations.booking.index') }}" class="btn btn-secondary">Back to Bookings</a>
                        
                        @if($booking->status == 'pending' || $booking->status == 'confirmed')
                            <form action="{{ route('vaccinations.booking.cancel', $booking->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

