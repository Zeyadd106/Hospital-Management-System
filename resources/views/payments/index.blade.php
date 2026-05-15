@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment History</h5>
                    <div>
                        <a href="{{ route('payments.methods') }}" class="btn btn-sm btn-secondary me-2">Payment Methods</a>
                        <a href="{{ route('payments.create') }}" class="btn btn-sm btn-primary">Make a Payment</a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                            <td>{{ $payment->description }}</td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                @if($payment->status == 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($payment->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @elseif($payment->status == 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $payment->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5>No payment history</h5>
                            <p class="text-muted">You haven't made any payments yet.</p>
                            <a href="{{ route('payments.create') }}" class="btn btn-primary mt-2">Make a Payment</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
