@extends('doctor.layouts.app')

@section('title', 'Pharmacy Request Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Prescription Refill Details</h1>
        <a href="{{ route('doctor.user-pharmacy') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Refill Request #{{ $prescriptionRefill->id }}</h6>
                    <div>
                        @if($prescriptionRefill->status == 'pending')
                            <form action="{{ route('doctor.user-pharmacy.approve', $prescriptionRefill->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Patient Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $prescriptionRefill->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $prescriptionRefill->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $prescriptionRefill->user->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Prescription Information</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Prescription</th>
                                    <td>{{ $prescriptionRefill->prescription->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($prescriptionRefill->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($prescriptionRefill->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($prescriptionRefill->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @elseif($prescriptionRefill->status == 'completed')
                                            <span class="badge badge-info">Completed</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Requested At</th>
                                    <td>{{ $prescriptionRefill->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @if($prescriptionRefill->approved_at)
                                <tr>
                                    <th>Approved At</th>
                                    <td>{{ $prescriptionRefill->approved_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                                @if($prescriptionRefill->rejected_at)
                                <tr>
                                    <th>Rejected At</th>
                                    <td>{{ $prescriptionRefill->rejected_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                                @if($prescriptionRefill->completed_at)
                                <tr>
                                    <th>Completed At</th>
                                    <td>{{ $prescriptionRefill->completed_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="font-weight-bold">Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    {{ $prescriptionRefill->notes ?? 'No notes provided.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($prescriptionRefill->rejection_reason)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="font-weight-bold">Rejection Reason</h5>
                            <div class="card">
                                <div class="card-body bg-light">
                                    {{ $prescriptionRefill->rejection_reason }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Prescription Refill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('doctor.user-pharmacy.reject', $prescriptionRefill->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Reason for Rejection</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="5" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
