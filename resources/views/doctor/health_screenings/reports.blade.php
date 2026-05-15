@extends('layouts.doctor')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Health Screening Bookings Report</h3>
            <div class="card-tools">
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($bookings->isEmpty())
                <div class="alert alert-info">
                    No health screening bookings found for the selected period.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient Name</th>
                                <th>Screening Type</th>
                                <th>Appointment Date</th>
                                <th>Clinic</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->test->name }}</td>
                                <td>{{ $booking->appointment_date->format('F j, Y') }}</td>
                                <td>{{ $booking->clinic->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $booking->status === 'pending' ? 'warning' : ($booking->status === 'confirmed' ? 'success' : 'danger') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <h4>Report Summary</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-calendar-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Bookings</span>
                                    <span class="info-box-number">{{ $bookings->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Completed</span>
                                    <span class="info-box-number">{{ $bookings->where('status', 'completed')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pending</span>
                                    <span class="info-box-number">{{ $bookings->where('status', 'pending')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rejected</span>
                                    <span class="info-box-number">{{ $bookings->where('status', 'rejected')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .card-tools, .info-box-icon {
            display: none !important;
        }
        body {
            margin: 0;
            padding: 0;
        }
    }
</style>
@endpush
@endsection
