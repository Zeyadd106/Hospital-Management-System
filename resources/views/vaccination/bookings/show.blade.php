@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Vaccination Booking Details') }}
                    <a href="{{ route('vaccination.booking.index') }}" class="btn btn-secondary btn-sm">
                        Back to Bookings
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Vaccine:</strong>
                            {{ $booking->vaccine->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Clinic:</strong>
                            {{ $booking->clinic->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Appointment Date:</strong>
                            {{ \Carbon\Carbon::parse($booking->appointment_date)->format('d M Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Appointment Time:</strong>
                            {{ \Carbon\Carbon::parse($booking->appointment_time)->format('h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <span class="badge 
                                @if($booking->status == 'pending') bg-warning
                                @elseif($booking->status == 'confirmed') bg-success
                                @elseif($booking->status == 'cancelled') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Confirmation Code:</strong>
                            {{ $booking->confirmation_code }}
                        </div>
                    </div>

                    @if($booking->notes)
                        <div class="row mb-3">
                            <div class="col-12">
                                <strong>Additional Notes:</strong>
                                {{ $booking->notes }}
                            </div>
                        </div>
                    @endif

                    @if($booking->status == 'pending')
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('vaccination.booking.edit', $booking->id) }}" class="btn btn-warning me-2">
                                    Edit Booking
                                </a>
                                <form action="{{ route('vaccination.booking.destroy', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                        Cancel Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
