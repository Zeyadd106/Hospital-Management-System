@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">{{ __('Profile Information') }}</div>
                <div class="card-body text-center">
                    <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}" class="rounded-circle mb-3" width="150" height="150" alt="Profile Picture">
                    <h4>{{ $user->name }}</h4>
                    <p>{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Vaccination Bookings -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Recent Vaccination Bookings') }}
                    <a href="{{ route('vaccinations.bookings.index') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($vaccinationBookings->isEmpty())
                        <p class="text-center">No vaccination bookings yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Vaccine</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vaccinationBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->vaccine->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($booking->status == 'pending') bg-warning
                                                    @elseif($booking->status == 'confirmed') bg-success
                                                    @elseif($booking->status == 'cancelled') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('vaccinations.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Health Screening Bookings -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Recent Health Screening Bookings') }}
                    <a href="{{ route('health-screenings.index') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($healthScreeningBookings->isEmpty())
                        <p class="text-center">No health screening bookings yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Screening</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($healthScreeningBookings as $booking)
                                        <tr>
                                            <td>{{ $booking->healthScreening->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($booking->status == 'pending') bg-warning
                                                    @elseif($booking->status == 'confirmed') bg-success
                                                    @elseif($booking->status == 'cancelled') bg-danger
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('health-screenings.show', $booking->id) }}" class="btn btn-sm btn-info">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
