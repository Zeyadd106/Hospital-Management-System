@extends('layouts.doctor')

@section('content')
<div class="container">
    <h1 class="mb-4">Vaccination Bookings</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            Vaccination Booking List
        </div>
        <div class="card-body">
            @if($bookings->isEmpty())
                <p class="text-center">No vaccination bookings found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Patient</th>
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
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->vaccine->name }}</td>
                                    <td>{{ $booking->clinic->name }}</td>
                                    <td>{{ $booking->appointment_date->format('d M Y') }}</td>
                                    <td>{{ $booking->appointment_time }}</td>
                                    <td>
                                        <span class="badge 
                                            @switch($booking->status)
                                                @case('pending') bg-warning @break
                                                @case('confirmed') bg-info @break
                                                @case('completed') bg-success @break
                                                @case('rejected') bg-danger @break
                                                @default bg-secondary
                                            @endswitch
                                        ">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('doctor.vaccinations.accept', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Accept
                                                    </button>
                                                </form>
                                                <form action="{{ route('doctor.vaccinations.reject', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $bookings->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
