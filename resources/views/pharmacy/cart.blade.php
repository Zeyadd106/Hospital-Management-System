@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Your Cart</h4>
                </div>
                <div class="card-body">
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

                    @if(count($cart) > 0)
                        <form action="{{ route('pharmacy.cart.update') }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Medication</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $total = 0 @endphp
                                        @foreach($cart as $id => $details)
                                            @php $total += $details['price'] * $details['quantity'] @endphp
                                            <tr>
                                                <td class="align-middle">
                                                    <div class="d-flex align-items-center">
                                                        @if($details['image'])
                                                            <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" 
                                                                 class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: contain;">
                                                        @else
                                                            <div class="bg-light me-3 d-flex align-items-center justify-content-center" 
                                                                 style="width: 60px; height: 60px;">
                                                                <i class="fas fa-pills fa-2x text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $details['name'] }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">${{ number_format($details['price'], 2) }}</td>
                                                <td class="align-middle" style="width: 150px;">
                                                    <div class="input-group">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                                data-action="decrease" data-id="{{ $id }}">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" name="quantities[{{ $id }}]" value="{{ $details['quantity'] }}" 
                                                               class="form-control form-control-sm text-center quantity-input" 
                                                               min="1" max="10">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                                data-action="increase" data-id="{{ $id }}">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="align-middle">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                                <td class="align-middle">
                                                    <a href="{{ route('pharmacy.cart.remove', $id) }}" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td><strong>${{ number_format($total, 2) }}</strong></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('pharmacy.medications') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                                </a>
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-sync-alt me-2"></i>Update Cart
                                    </button>
                                    <a href="{{ route('checkout') }}" class="btn btn-success">
                                        <i class="fas fa-shopping-bag me-2"></i>Proceed to Checkout
                                    </a>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h5>Your cart is empty</h5>
                            <p class="text-muted">Add some medications to your cart and they will appear here.</p>
                            <a href="{{ route('pharmacy.medications') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-pills me-2"></i>Browse Medications
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle quantity buttons
        document.querySelectorAll('.quantity-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const action = this.dataset.action;
                const id = this.dataset.id;
                const input = this.closest('tr').querySelector('.quantity-input');
                let value = parseInt(input.value);
                
                if (action === 'increase' && value < 10) {
                    input.value = value + 1;
                } else if (action === 'decrease' && value > 1) {
                    input.value = value - 1;
                }
            });
        });
    });
</script>
@endsection
