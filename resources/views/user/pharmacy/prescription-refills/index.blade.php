@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Prescription Refill Requests</h4>
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
                                    <th>Medication</th>
                                    <th>Quantity</th>
                                    <th>Prescription</th>
                                    <th>Status</th>
                                    <th>Requested At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($refills as $refill)
                                    <tr>
                                        <td>
                                            {{ $refill->medication->name }}
                                            <br>
                                            <small class="text-muted">
                                                ${{ number_format($refill->medication->price, 2) }} per unit
                                            </small>
                                        </td>
                                        <td>{{ $refill->quantity }} units</td>
                                        <td>
                                            <a href="{{ route('user.pharmacy.prescription-refills.show', $refill->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                View Prescription
                                            </a>
                                        </td>
                                        <td>
                                            @if($refill->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($refill->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($refill->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $refill->created_at->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <a href="{{ route('user.pharmacy.prescription-refills.show', $refill->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $refills->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
