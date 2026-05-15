@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5">My Vaccination Appointments</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Verify Booking</h5>
                    <form action="{{ route('vaccinations.booking.verify') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="text" name="confirmation_code" class="form-control me-2" placeholder="Enter confirmation code" required>
                        <button type="submit" class="btn btn-primary">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if(count($bookings) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Vaccine</th>
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
                            <td>{{ \Carbon\Carbon::parse($booking->appointment_date)->format('M d, Y') }}</td>
                            <td>{{ $booking->time_slot }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status_badge }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('vaccinations.booking.show', $booking->id) }}" class="btn btn-sm btn-info">View</a>
                                
                                @if($booking->status == 'pending' || $booking->status == 'confirmed')
                                    <form action="{{ route('vaccinations.booking.cancel', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info text-center">
            <p>You don't have any vaccination appointments yet.</p>
            <a href="{{ route('vaccinations.index') }}" class="btn btn-primary mt-2">Book a Vaccination</a>
        </div>
    @endif
</div>
@endsection

