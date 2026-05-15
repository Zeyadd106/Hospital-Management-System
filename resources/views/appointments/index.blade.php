@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-center mb-5">My Appointments</h2>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">Book Appointment</a>
    </div>
    
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
                    <h5 class="card-title">Verify Appointment</h5>
                    <form action="{{ route('appointments.verify.code') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="text" name="confirmation_code" class="form-control me-2" placeholder="Enter confirmation code" required>
                        <button type="submit" class="btn btn-primary">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @if(count($appointments) > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>
                                {{ $appointment->doctor ? $appointment->doctor->name : 'Doctor Not Assigned' }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                            <td>{{ $appointment->appointment_time }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $appointment->status === 'confirmed' ? 'success' : 
                                    ($appointment->status === 'pending' ? 'warning' : 'secondary') 
                                }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                
                                @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
                                    <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</button>
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
            <p>You don't have any appointments yet.</p>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary mt-2">Book an Appointment</a>
        </div>
    @endif
</div>
@endsection
