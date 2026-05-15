@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h4 mb-0">Health Screening Booking Details</h2>
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
                            <h5>Screening</h5>
                            <p>{{ $booking->healthScreening->name }}</p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Date</h5>
                            <p>{{ \Carbon\Carbon::parse($booking->booking_date)->format('l, F j, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Time</h5>
                            <p>{{ $booking->booking_time }}</p>
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
                        <h5 class="alert-heading">Screening Information</h5>
                        <p class="mb-2">{{ $booking->healthScreening->description }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="d-block"><strong>Duration:</strong> {{ $booking->healthScreening->duration }} minutes</small>
                                <small class="d-block"><strong>Recommended For:</strong> {{ ucfirst($booking->healthScreening->recommended_age_group) }}</small>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Price:</strong> ${{ number_format($booking->healthScreening->price, 2) }}</small>
                                <small class="d-block"><strong>Payment Status:</strong> {{ ucfirst($booking->payment_status) }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('health-screenings.booking.index') }}" class="btn btn-secondary">Back to Bookings</a>
                        
                        @if($booking->status == 'pending' || $booking->status == 'confirmed')
                            <form action="{{ route('health-screenings.booking.cancel', $booking->id) }}" method="POST">
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

