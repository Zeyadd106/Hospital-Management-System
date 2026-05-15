@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fa fa-shopping-cart me-2"></i>Your Shopping Cart</h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(empty($cartItems))
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fa fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Looks like you haven't added any medications to your cart yet.</p>
                <a href="{{ route('user.pharmacy.medications.index') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-pills me-2"></i>Browse Medications
                </a>
            </div>
        </div>
    @else
        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Cart Items ({{ count($cartItems) }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Medication</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item['medication']->image_path)
                                                        <img src="{{ asset($item['medication']->image_path) }}" alt="{{ $item['medication']->name }}" class="me-3" style="width: 50px; height: 50px; object-fit: contain;">
                                                    @else
                                                        <div class="me-3 bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
                                            <td>
                                                <form action="{{ route('user.pharmacy.cart.update') }}" method="POST" class="quantity-form">
                                                    @csrf
                                                    <input type="hidden" name="medication_id" value="{{ $item['medication']->id }}">
                                                    <div class="input-group input-group-sm" style="width: 100px;">
                                                        <button type="button" class="btn btn-outline-secondary quantity-decrease">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <input type="number" name="quantity" class="form-control text-center quantity-input" value="{{ $item['quantity'] }}" min="1" max="10">
                                                        <button type="button" class="btn btn-outline-secondary quantity-increase">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>${{ number_format($item['subtotal'], 2) }}</td>
                                            <td>
                                                <a href="{{ route('user.pharmacy.cart.remove', $item['medication']->id) }}" class="btn btn-sm btn-outline-danger remove-item">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('user.pharmacy.medications.index') }}" class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <a href="{{ route('user.pharmacy.cart.clear') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-trash me-2"></i>Clear Cart
                        </a>
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
                        <a href="{{ route('user.pharmacy.cart.checkout') }}" class="btn btn-success w-100">
                            <i class="fa fa-credit-card me-2"></i>Proceed to Checkout
                        </a>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="fa fa-shield-alt me-2"></i>Secure Checkout</h6>
                        <p class="small text-muted mb-0">Your payment information is processed securely. We do not store credit card details nor have access to your credit card information.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle quantity increase/decrease
        document.querySelectorAll('.quantity-decrease').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                    submitForm(this);
                }
            });
        });

        document.querySelectorAll('.quantity-increase').forEach(function(button) {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max'));
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    submitForm(this);
                }
            });
        });

        // Submit form when quantity changes
        document.querySelectorAll('.quantity-input').forEach(function(input) {
            input.addEventListener('change', function() {
                submitForm(this);
            });
        });

        // Function to submit the form
        function submitForm(element) {
            const form = element.closest('.quantity-form');
            form.submit();
        }

        // Confirm remove item
        document.querySelectorAll('.remove-item').forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to remove this item from your cart?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endsection
