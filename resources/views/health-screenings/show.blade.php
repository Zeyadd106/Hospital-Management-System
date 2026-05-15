@extends('layouts.app')

@section('additional_styles')
<style>
    .booking-details-section {
        padding: 50px 0;
    }
    
    .booking-details-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .booking-details-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .booking-details-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .booking-details-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
        color: var(--primary-color);
    }
    
    .booking-info-item {
        margin-bottom: 15px;
    }
    
    .booking-info-label {
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .booking-status {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: bold;
    }
    
    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    
    .status-confirmed {
        background-color: #17a2b8;
        color: white;
    }
    
    .status-completed {
        background-color: #28a745;
        color: white;
    }
    
    .status-cancelled {
        background-color: #dc3545;
        color: white;
    }
    
    .confirmation-code {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        font-size: 18px;
        font-weight: bold;
        margin: 20px 0;
        letter-spacing: 2px;
        text-align: center;
    }
    
    .action-buttons {
        margin-top: 30px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="booking-details-section">
    <div class="container">
        <div class="booking-details-header">
            <h2>Health Screening Booking Details</h2>
            <p>Review your health screening appointment details below</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="booking-details-card">
                    <h3 class="booking-details-title">Booking Information</h3>
                    
                    <div class="booking-info-item">
                        <div class="booking-info-label">Screening Type</div>
                        <div>{{ $booking->screening_type }}</div>
                    </div>
                    
                    <div class="booking-info-item">
                        <div class="booking-info-label">Appointment Date</div>
                        <div>{{ date('l, F d, Y', strtotime($booking->appointment_date)) }}</div>
                    </div>
                    
                    <div class="booking-info-item">
                        <div class="booking-info-label">Appointment Time</div>
                        <div>{{ date('h:i A', strtotime($booking->appointment_time)) }}</div>
                    </div>
                    
                    <div class="booking-info-item">
                        <div class="booking-info-label">Status</div>
                        <div>
                            <span class="booking-status status-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($booking->notes)
                        <div class="booking-info-item">
                            <div class="booking-info-label">Additional Notes</div>
                            <div>{{ $booking->notes }}</div>
                        </div>
                    @endif
                    
                    <div class="booking-info-item">
                        <div class="booking-info-label">Booking Date</div>
                        <div>{{ date('F d, Y \a\t h:i A', strtotime($booking->created_at)) }}</div>
                    </div>
                    
                    <div class="confirmation-code">
                        Confirmation Code: {{ $booking->confirmation_code }}
                    </div>
                    
                    <div class="action-buttons">
                        @if($booking->status == 'pending')
                            <a href="{{ route('health-screenings.edit', $booking->id) }}" class="btn btn-primary me-2">Edit Booking</a>
                            
                            <form action="{{ route('health-screenings.destroy', $booking->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel Booking</button>
                            </form>
                        @endif
                        
                        <a href="{{ route('health-screenings.index') }}" class="btn btn-secondary ms-2">Back to Health Screenings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

