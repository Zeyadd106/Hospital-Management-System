@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Prescription Refill Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Medication</h5>
                            <p class="card-text">
                                <strong>Name:</strong> {{ $refill->medication->name }}<br>
                                <strong>Price:</strong> ${{ number_format($refill->medication->price, 2) }}<br>
                                <strong>Quantity:</strong> {{ $refill->quantity }} units<br>
                                <strong>Total:</strong> ${{ number_format($refill->medication->price * $refill->quantity, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Prescription</h5>
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $refill->prescription_image) }}" 
                                     class="img-fluid rounded" 
                                     alt="Prescription Image"
                                     style="max-height: 400px; object-fit: contain;">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Status</h5>
                        <div class="badge bg-{{ 
                            $refill->status === 'pending' ? 'warning text-dark' : 
                            $refill->status === 'approved' ? 'success' : 
                            'danger' 
                        }}">
                            {{ ucfirst($refill->status) }}
                        </div>
                    </div>

                    @if($refill->status === 'approved' && $refill->medication->stock->quantity > 0)
                        <div class="mt-4">
                            <a href="{{ route('user.pharmacy.cart.add', ['id' => $refill->medication->id, 'quantity' => $refill->quantity]) }}" 
                               class="btn btn-primary">
                                <i class="fa fa-shopping-cart me-2"></i>Add to Cart
                            </a>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('user.pharmacy.prescription-refills.index') }}" 
                           class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-2"></i>Back to Refills
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
