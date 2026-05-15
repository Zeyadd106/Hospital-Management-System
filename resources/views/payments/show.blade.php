@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Details</h5>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-secondary">Back to Payments</a>
                </div>

                <div class="card-body">
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
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment ID</h6>
                            <p class="font-weight-bold">{{ $payment->id }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Date</h6>
                            <p class="font-weight-bold">{{ $payment->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Amount</h6>
                            <p class="font-weight-bold">${{ number_format($payment->amount, 2) }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="text-muted">Status</h6>
                            <p>
                                @if($payment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($payment->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($payment->status == 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">{{ $payment->status }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Description</h6>
                            <p>{{ $payment->description }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Payment Method</h6>
                            @if($payment->payment_method_id && $payment->paymentMethod)
                                <p>
                                    @if($payment->paymentMethod->type == 'credit_card')
                                        <i class="fa fa-credit-card me-2"></i> Credit Card ({{ $payment->paymentMethod->card_number }})
                                    @elseif($payment->paymentMethod->type == 'debit_card')
                                        <i class="fa fa-credit-card me-2"></i> Debit Card ({{ $payment->paymentMethod->card_number }})
                                    @elseif($payment->paymentMethod->type == 'bank_transfer')
                                        <i class="fa fa-university me-2"></i> Bank Transfer ({{ $payment->paymentMethod->bank_name }})
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $payment->paymentMethod->type)) }}
                                    @endif
                                </p>
                            @elseif(strpos($payment->transaction_id, 'DEMO-') === 0)
                                <p><i class="fa fa-money-bill-wave me-2"></i> <span class="text-success">Instant Test Payment (Demo)</span></p>
                            @else
                                <p>Not specified</p>
                            @endif
                        </div>
                    </div>

                    @if($payment->transaction_id)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Transaction ID</h6>
                            <p>{{ $payment->transaction_id }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="text-center mt-4">
                        @if($payment->status == 'completed')
                            <a href="{{ route('payments.download', $payment->id) }}" class="btn btn-primary"><i class="fa fa-download me-2"></i> Download Receipt</a>
                        @else
                            <button class="btn btn-secondary" disabled><i class="fa fa-download me-2"></i> Receipt Unavailable</button>
                            <small class="d-block text-muted mt-2">Receipts are only available for completed payments</small>
                        @endif
                        <a href="#" class="btn btn-outline-primary ms-2" onclick="window.print();"><i class="fa fa-print me-2"></i> Print Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
