@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Appointments</h3>
                    <a href="{{ route('doctor.schedule') }}" class="btn btn-primary">Schedule New Appointment</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Clinic</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ $appointment->date->format('M d, Y') }}</td>
                                    <td>{{ $appointment->time }}</td>
                                    <td>{{ $appointment->clinic->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('doctor.appointments.edit', $appointment->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('doctor.appointments.destroy', $appointment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
