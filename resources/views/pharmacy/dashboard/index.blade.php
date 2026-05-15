@extends('pharmacy.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Refills</h5>
                                    <h2>{{ $pendingRefills }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Processed Refills</h5>
                                    <h2>{{ $processedRefills }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Rejected Refills</h5>
                                    <h2>{{ $rejectedRefills }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Recent Prescription Refills</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Prescription #</th>
                                                <th>Patient</th>
                                                <th>Doctor</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($refills as $refill)
                                            <tr>
                                                <td>{{ $refill->prescription->id }}</td>
                                                <td>{{ $refill->prescription->patient->name }}</td>
                                                <td>{{ $refill->prescription->doctor->name }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $refill->status === 'pending' ? 'warning' : ($refill->status === 'processed' ? 'success' : 'danger') }}">
                                                        {{ ucfirst($refill->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($refill->status === 'pending')
                                                        <a href="{{ route('pharmacy.prescription-refill.approve', $refill->id) }}" class="btn btn-sm btn-success">
                                                            Approve
                                                        </a>
                                                        <a href="{{ route('pharmacy.prescription-refill.reject', $refill->id) }}" class="btn btn-sm btn-danger">
                                                            Reject
                                                        </a>
                                                    @elseif($refill->status === 'approved')
                                                        <a href="{{ route('pharmacy.prescription-refill.process', $refill->id) }}" class="btn btn-sm btn-primary">
                                                            Process
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
