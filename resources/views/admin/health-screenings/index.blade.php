@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Health Screening Bookings</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.health-screenings.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Booking
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($bookings->isEmpty())
                        <div class="alert alert-info">
                            No health screening bookings found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient Name</th>
                                        <th>Screening Type</th>
                                        <th>Date</th>
                                        <th>Clinic</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->healthScreening->name }}</td>
                                        <td>{{ $booking->appointment_date->format('F j, Y') }}</td>
                                        <td>{{ $booking->clinic->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $booking->status === 'pending' ? 'warning' : ($booking->status === 'confirmed' ? 'success' : 'danger') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.health-screenings.show', $booking->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('admin.health-screenings.edit', $booking->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection