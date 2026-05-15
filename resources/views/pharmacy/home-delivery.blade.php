@extends('layouts.app')

@section('content')
<div class="main-content" style="align-items: flex-start;">
    <div class="container py-5">
        <h1 class="text-center mb-5">Medication Home Delivery Service</h1>
        
        <div class="row mb-5">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <img src="{{ asset('images/pharmacy/delivery.jpg') }}" alt="Medication delivery" class="img-fluid rounded shadow-sm">
            </div>
            <div class="col-lg-6 order-lg-1">
                <h2 class="mb-4">Convenient Medication Delivery</h2>
                <p class="lead">Get your prescriptions delivered right to your doorstep with our safe and reliable home delivery service.</p>
                <p>Our medication home delivery service offers:</p>
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><i class="fas fa-truck text-primary me-2"></i> Free delivery within 10 miles of our pharmacy</li>
                    <li class="list-group-item"><i class="fas fa-shield-alt text-primary me-2"></i> Secure packaging to ensure medication safety</li>
                    <li class="list-group-item"><i class="fas fa-clock text-primary me-2"></i> Same-day delivery for orders placed before noon</li>
                    <li class="list-group-item"><i class="fas fa-mobile-alt text-primary me-2"></i> Text notifications when your delivery is on the way</li>
                    <li class="list-group-item"><i class="fas fa-temperature-low text-primary me-2"></i> Temperature-controlled delivery for sensitive medications</li>
                </ul>
                <a href="#delivery-form" class="btn btn-primary">Order Now</a>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">How It Works</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-prescription fa-2x text-primary"></i>
                                </div>
                                <h4>Step 1</h4>
                                <p>Submit your prescription through our website or app</p>
                            </div>
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-pills fa-2x text-primary"></i>
                                </div>
                                <h4>Step 2</h4>
                                <p>Our pharmacists prepare your medication</p>
                            </div>
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-credit-card fa-2x text-primary"></i>
                                </div>
                                <h4>Step 3</h4>
                                <p>Complete payment online or upon delivery</p>
                            </div>
                            <div class="col-md-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-truck fa-2x text-primary"></i>
                                </div>
                                <h4>Step 4</h4>
                                <p>Receive your medication at your doorstep</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" id="delivery-form">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Request Medication Delivery</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pharmacy.prescription-refill.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="delivery_requested" value="1">
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Delivery Address</label>
                                <input type="text" class="form-control mb-2" id="address" name="delivery_address" placeholder="Street Address" required>
                                <input type="text" class="form-control mb-2" id="address2" name="address2" placeholder="Apartment, suite, etc. (optional)">
                                <div class="row">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <input type="text" class="form-control" id="state" name="state" placeholder="State" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="ZIP Code" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prescription_number" class="form-label">Prescription Number (if known)</label>
                                <input type="text" class="form-control" id="prescription_number" name="prescription_number">
                            </div>
                            
                            <div class="mb-3">
                                <label for="medication_details" class="form-label">Prescription Details</label>
                                <textarea class="form-control" id="medication_details" name="medication_details" rows="3" placeholder="Please provide prescription number, medication name, doctor's name, etc." required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Delivery Instructions (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Special instructions for delivery"></textarea>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Submit Delivery Request</button>
                                <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary">Back to Pharmacy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
