@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fa fa-credit-card me-2"></i>Checkout</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.pharmacy.medications.index') }}">Medications</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.pharmacy.cart.index') }}">Shopping Cart</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('user.pharmacy.cart.process') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <!-- Order Details -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Medication</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item['medication']->image_path)
                                                        <img src="{{ asset($item['medication']->image_path) }}" alt="{{ $item['medication']->name }}" class="me-3" style="width: 40px; height: 40px; object-fit: contain;">
                                                    @else
                                                        <div class="me-3 bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fa fa-pills text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $item['medication']->name }}</h6>
                                                        <small class="text-muted">{{ $item['medication']->manufacturer }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item['medication']->price, 2) }}</td>
                                            <td>{{ $item['quantity'] }}</td>
                                            <td class="text-end">${{ number_format($item['subtotal'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end">${{ number_format($totalAmount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                        <td class="text-end">$5.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong>${{ number_format($totalAmount + 5, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Delivery Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address</label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" rows="3" required>{{ old('delivery_address', auth()->user()->address) }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $paymentMethods = App\Models\PaymentMethod::where('user_id', auth()->id())->get();
                            @endphp

                            @if($paymentMethods->isEmpty())
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle me-2"></i>You don't have any saved payment methods.
                                    </div>
                                    <a href="{{ route('payments.methods') }}" class="btn btn-primary" target="_blank">
                                        <i class="fa fa-plus-circle me-2"></i>Add Payment Method
                                    </a>
                                </div>
                            @else
                                @foreach($paymentMethods as $method)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100 @if(old('payment_method_id') == $method->id) border-primary @endif">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method_id" id="payment_method_{{ $method->id }}" value="{{ $method->id }}" @if(old('payment_method_id') == $method->id) checked @endif required>
                                                    <label class="form-check-label" for="payment_method_{{ $method->id }}">
                                                        @if($method->type == 'credit_card')
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa fa-credit-card text-primary me-2"></i>
                                                                <div>
                                                                    <strong>{{ ucfirst($method->card_type) }}</strong> ending in {{ substr($method->card_number, -4) }}<br>
                                                                    <small class="text-muted">Expires: {{ $method->expiry_month }}/{{ $method->expiry_year }}</small>
                                                                </div>
                                                            </div>
                                                        @elseif($method->type == 'paypal')
                                                            <div class="d-flex align-items-center">
                                                                <i class="fab fa-paypal text-primary me-2"></i>
                                                                <div>
                                                                    <strong>PayPal</strong><br>
                                                                    <small class="text-muted">{{ $method->paypal_email }}</small>
                                                                </div>
                                                            </div>
                                                        @elseif($method->type == 'fake')
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa fa-money-bill-wave text-success me-2"></i>
                                                                <div>
                                                                    <strong>Demo Payment</strong><br>
                                                                    <small class="text-muted">For testing purposes</small>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @error('payment_method_id')
                                <div class="col-12">
                                    <div class="text-danger">{{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal:</span>
                            <span>${{ number_format($totalAmount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Shipping:</span>
                            <span>$5.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>${{ number_format($totalAmount + 5, 2) }}</strong>
                        </div>
                        <button type="submit" class="btn btn-success w-100" @if($paymentMethods->isEmpty()) disabled @endif>
                            <i class="fa fa-lock me-2"></i>Place Order
                        </button>
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fa fa-shield-alt me-1"></i>Your payment information is processed securely.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="mb-3">Order Policy</h6>
                        <ul class="small text-muted ps-3">
                            <li class="mb-2">Orders are typically processed within 24 hours</li>
                            <li class="mb-2">Delivery time: 1-3 business days</li>
                            <li class="mb-2">Prescription medications require verification</li>
                            <li>Refunds available within 30 days for unopened items</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
