@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Methods</h5>
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

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Your Payment Methods</h5>
                            @if($paymentMethods->count() > 0)
                                <div class="list-group mt-3">
                                    @foreach($paymentMethods as $method)
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($method->type == 'credit_card')
                                                    <i class="fa fa-credit-card me-2"></i> Credit Card
                                                    <div class="text-muted small">{{ $method->card_number }} (Expires: {{ $method->expiry_date }})</div>
                                                @elseif($method->type == 'debit_card')
                                                    <i class="fa fa-credit-card me-2"></i> Debit Card
                                                    <div class="text-muted small">{{ $method->card_number }} (Expires: {{ $method->expiry_date }})</div>
                                                @elseif($method->type == 'bank_transfer')
                                                    <i class="fa fa-university me-2"></i> Bank Transfer
                                                    <div class="text-muted small">{{ $method->bank_name }} ({{ $method->account_number }})</div>
                                                @else
                                                    <i class="fa fa-money-bill me-2"></i> {{ ucfirst(str_replace('_', ' ', $method->type)) }}
                                                @endif
                                            </div>
                                            <form action="{{ route('payments.methods.destroy', $method->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this payment method?')">Remove</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info mt-3">
                                    You don't have any saved payment methods yet.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h5>Add New Payment Method</h5>
                            <form action="{{ route('payments.methods.store') }}" method="POST" class="mt-3" id="payment-method-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="type" class="form-label">Payment Type</label>
                                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Select payment type</option>
                                        <option value="credit_card" {{ old('type') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="debit_card" {{ old('type') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                        <option value="bank_transfer" {{ old('type') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="card_fields" style="display: {{ old('type') == 'credit_card' || old('type') == 'debit_card' ? 'block' : 'none' }}">
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" name="card_number" id="card_number" value="{{ old('card_number') }}" class="form-control @error('card_number') is-invalid @enderror" placeholder="1234 5678 9012 3456">
                                        @error('card_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="form-control @error('expiry_date') is-invalid @enderror" placeholder="MM/YY">
                                        @error('expiry_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" name="cvv" id="cvv" value="{{ old('cvv') }}" class="form-control @error('cvv') is-invalid @enderror" placeholder="123">
                                        @error('cvv')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="bank_fields" style="display: {{ old('type') == 'bank_transfer' ? 'block' : 'none' }}">
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" class="form-control @error('bank_name') is-invalid @enderror">
                                        @error('bank_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="account_number" class="form-label">Account Number</label>
                                        <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" class="form-control @error('account_number') is-invalid @enderror">
                                        @error('account_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Add Payment Method</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const cardFields = document.getElementById('card_fields');
        const bankFields = document.getElementById('bank_fields');

        typeSelect.addEventListener('change', function() {
            if (this.value === 'credit_card' || this.value === 'debit_card') {
                cardFields.style.display = 'block';
                bankFields.style.display = 'none';
            } else if (this.value === 'bank_transfer') {
                cardFields.style.display = 'none';
                bankFields.style.display = 'block';
            } else {
                cardFields.style.display = 'none';
                bankFields.style.display = 'none';
            }
        });

        // Format card number with spaces
        const cardNumberInput = document.getElementById('card_number');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 16) value = value.slice(0, 16);
                
                // Add spaces every 4 digits
                const parts = [];
                for (let i = 0; i < value.length; i += 4) {
                    parts.push(value.slice(i, i + 4));
                }
                
                e.target.value = parts.join(' ');
            });
        }

        // Format expiry date as MM/YY
        const expiryDateInput = document.getElementById('expiry_date');
        if (expiryDateInput) {
            expiryDateInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 0) {
                    // First digit can only be 0 or 1
                    if (value[0] > 1) value = '0' + value;
                    
                    // Second digit can only be 0-2 if first digit is 1
                    if (value[0] == 1 && value.length > 1 && value[1] > 2) {
                        value = value[0] + '2' + value.slice(2);
                    }
                }
                
                if (value.length > 4) value = value.slice(0, 4);
                
                if (value.length > 2) {
                    e.target.value = value.slice(0, 2) + '/' + value.slice(2);
                } else {
                    e.target.value = value;
                }
            });
        }

        // Limit CVV to 3-4 digits
        const cvvInput = document.getElementById('cvv');
        if (cvvInput) {
            cvvInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 4) value = value.slice(0, 4);
                e.target.value = value;
            });
        }
    });
</script>
@endpush
@endsection
