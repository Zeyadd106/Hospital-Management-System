@extends('doctor.layouts.app')

@section('title', 'Pharmacy Requests')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pharmacy Requests</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Prescription Refill Requests</h6>
                </div>
                <div class="card-body">
                    @if($prescriptionRefills->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patient</th>
                                        <th>Prescription</th>
                                        <th>Status</th>
                                        <th>Requested At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prescriptionRefills as $refill)
                                        <tr>
                                            <td>{{ $refill->id }}</td>
                                            <td>{{ $refill->user->name }}</td>
                                            <td>{{ $refill->prescription->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($refill->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($refill->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($refill->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @elseif($refill->status == 'completed')
                                                    <span class="badge badge-info">Completed</span>
                                                @endif
                                            </td>
                                            <td>{{ $refill->created_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                <a href="{{ route('doctor.user-pharmacy.show', $refill->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                @if($refill->status == 'pending')
                                                    <form action="{{ route('doctor.user-pharmacy.approve', $refill->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal{{ $refill->id }}">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $prescriptionRefills->links() }}
                        </div>
                    @else
                        <p>No prescription refill requests found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modals -->
@foreach($prescriptionRefills as $refill)
    <div class="modal fade" id="rejectModal{{ $refill->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel{{ $refill->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel{{ $refill->id }}">Reject Prescription Refill</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('doctor.user-pharmacy.reject', $refill->id) }}" method="POST">
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
@endforeach
@endsection
