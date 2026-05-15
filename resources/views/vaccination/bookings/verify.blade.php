@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Vaccination Booking') }}</div>

                <div class="card-body">
                    <div class="alert alert-info">
                        Please verify your vaccination booking details below.
                    </div>

                    <div class="booking-details">
                        <h4>Booking Details</h4>
                        <table class="table">
                            <tr>
                                <th>Booking ID:</th>
                                <td>{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <th>Vaccine:</th>
                                <td>{{ $booking->vaccine->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Clinic:</th>
                                <td>{{ $booking->clinic->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $booking->appointment_date }}</td>
                            </tr>
                            <tr>
                                <th>Time:</th>
                                <td>{{ $booking->appointment_time }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><span class="badge bg-warning">{{ ucfirst($booking->status) }}</span></td>
                            </tr>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <form action="{{ route('vaccination.booking.confirm', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Confirm Booking</button>
                        </form>
                        
                        <form action="{{ route('vaccination.booking.cancel', $booking->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Cancel Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection