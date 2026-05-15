@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('User Dashboard') }}</div>

                <div class="card-body">
                    <h2>Welcome, {{ $user->name }}!</h2>

                    <div class="row">
                        {{-- Upcoming Appointments --}}
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">Upcoming Appointments</div>
                                <div class="card-body">
                                    @if($upcomingAppointments->count() > 0)
                                        @foreach($upcomingAppointments as $appointment)
                                            <div class="mb-2">
                                                <strong>{{ $appointment->doctor->name }}</strong>
                                                <br>
                                                Date: {{ $appointment->appointment_date->format('d M Y') }}
                                                <br>
                                                Time: {{ $appointment->appointment_time }}
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No upcoming appointments.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Vaccination Bookings --}}
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">Vaccination Bookings</div>
                                <div class="card-body">
                                    @if($vaccinationBookings->count() > 0)
                                        @foreach($vaccinationBookings as $booking)
                                            <div class="mb-2">
                                                <strong>{{ $booking->vaccine_type }}</strong>
                                                <br>
                                                Date: {{ $booking->booking_date->format('d M Y') }}
                                                <br>
                                                Status: {{ ucfirst($booking->status) }}
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No vaccination bookings.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Health Screening Bookings --}}
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">Health Screenings</div>
                                <div class="card-body">
                                    @if($healthScreeningBookings->count() > 0)
                                        @foreach($healthScreeningBookings as $screening)
                                            <div class="mb-2">
                                                <strong>{{ $screening->screening_type }}</strong>
                                                <br>
                                                Date: {{ $screening->booking_date->format('d M Y') }}
                                                <br>
                                                Status: {{ ucfirst($screening->status) }}
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No health screenings booked.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
