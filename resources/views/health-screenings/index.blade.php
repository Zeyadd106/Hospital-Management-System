@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Health Screenings</h3>
                    <div class="card-tools">
                        <a href="{{ route('health-screenings.booking.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Book New Screening
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Screening Type</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Clinic</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                    <tr>
                                        <td>{{ $booking->healthScreening->name }}</td>
                                        <td>{{ $booking->appointment_date->format('F j, Y') }}</td>
                                        <td>{{ $booking->appointment_time }}</td>
                                        <td>{{ $booking->clinic->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $booking->status === 'pending' ? 'warning' : ($booking->status === 'confirmed' ? 'success' : 'danger') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('health-screenings.booking.show', $booking->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($booking->status === 'pending')
                                                    <a href="{{ route('health-screenings.booking.edit', $booking->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No health screenings found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->total() > 0)
                        {{ $bookings->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
