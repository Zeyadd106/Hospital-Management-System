@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Make a Payment</h5>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-secondary">Back to Payments</a>
                </div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount ($)</label>
                            <input type="number" step="0.01" min="1" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_method_id" class="form-label">Payment Method</label>
                            @if($paymentMethods->count() > 0)
                                <select name="payment_method_id" id="payment_method_id" class="form-select @error('payment_method_id') is-invalid @enderror" required>
                                    <option value="">Select a payment method</option>
                                    <option value="fake_payment" class="text-success">💰 Instant Test Payment (For Demo)</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}">
                                            @if($method->type == 'credit_card')
                                                Credit Card ({{ $method->card_number }})
                                            @elseif($method->type == 'debit_card')
                                                Debit Card ({{ $method->card_number }})
                                            @elseif($method->type == 'bank_transfer')
                                                Bank Transfer ({{ $method->bank_name }})
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $method->type)) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_method_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <a href="{{ route('payments.methods') }}">Manage payment methods</a>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    You don't have any saved payment methods. <a href="{{ route('payments.methods') }}">Add a payment method</a> first.
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">I agree to the terms and conditions</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" {{ $paymentMethods->count() == 0 ? 'disabled' : '' }}>Process Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
