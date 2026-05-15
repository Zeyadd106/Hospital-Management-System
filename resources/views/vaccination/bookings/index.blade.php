@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Vaccination Bookings</h1>

    @if($bookings->isEmpty())
        <div class="alert alert-info">
            You have no vaccination bookings yet. 
            <a href="{{ route('vaccination.book.create') }}" class="btn btn-primary ml-2">Book a Vaccination</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Vaccine</th>
                        <th>Clinic</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->vaccine->name }}</td>
                        <td>{{ $booking->clinic->name }}</td>
                        <td>{{ $booking->appointment_date }}</td>
                        <td>{{ $booking->appointment_time }}</td>
                        <td>
                            <span class="badge 
                                @if($booking->status == 'pending') badge-warning
                                @elseif($booking->status == 'confirmed') badge-success
                                @elseif($booking->status == 'cancelled') badge-danger
                                @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('vaccination_booking.show', $booking->id) }}" class="btn btn-sm btn-info">View</a>
                            @if($booking->status == 'pending')
                                <a href="{{ route('vaccination_booking.edit', $booking->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $bookings->links() }}
    @endif
</div>
@endsection
