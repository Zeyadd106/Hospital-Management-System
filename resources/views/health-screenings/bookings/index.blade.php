@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('My Health Screening Bookings') }}
                    <a href="{{ route('health-screenings.book.create') }}" class="btn btn-primary btn-sm">
                        Book New Health Screening
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($bookings->isEmpty())
                        <div class="alert alert-info">
                            You have no health screening bookings yet.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Screening</th>
                                        <th>Clinic</th>
                                        <th>Appointment Date</th>
                                        <th>Appointment Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->healthScreening->name }}</td>
                                            <td>{{ $booking->clinic->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('d M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->appointment_time)->format('h:i A') }}</td>
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
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('health-screenings.show', $booking->id) }}" class="btn btn-sm btn-info">View</a>
                                                    @if($booking->status == 'pending')
                                                        <a href="{{ route('health-screenings.booking.edit', $booking->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                        <form action="{{ route('health-screenings.booking.destroy', $booking->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
