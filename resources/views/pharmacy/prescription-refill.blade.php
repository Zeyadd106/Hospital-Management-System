@extends('layouts.app')

@section('additional_styles')
<style>
    .prescription-refill-section {
        padding: 50px 0;
    }
    
    .prescription-refill-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .prescription-refill-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .prescription-refill-form {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .form-section {
        margin-bottom: 30px;
    }
    
    .form-section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
        color: var(--primary-color);
    }
    
    .submit-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        font-weight: bold;
    }
    
    .submit-btn:hover {
        background-color: var(--secondary-color);
    }
</style>
@endsection

@section('content')
<div class="prescription-refill-section">
    <div class="container">
        <div class="prescription-refill-header">
            <h2>Prescription Refill Request</h2>
            <p>Request a refill for your existing prescriptions quickly and easily.</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="prescription-refill-form">
                    <form action="{{ route('pharmacy.prescription-refill.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-section">
                            <h3 class="form-section-title">Personal Information</h3>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="form-section-title">Prescription Information</h3>
                            
                            <div class="mb-3">
                                <label for="prescription_number" class="form-label">Prescription Number (if known)</label>
                                <input type="text" class="form-control @error('prescription_number') is-invalid @enderror" id="prescription_number" name="prescription_number" value="{{ old('prescription_number') }}">
                                @error('prescription_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="medication_details" class="form-label">Medication Details</label>
                                <textarea class="form-control @error('medication_details') is-invalid @enderror" id="medication_details" name="medication_details" rows="4" placeholder="Please list the medications you need refilled, including name, dosage, and prescribing doctor if known." required>{{ old('medication_details') }}</textarea>
                                @error('medication_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="form-section-title">Delivery Options</h3>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="delivery_requested" name="delivery_requested" value="1" {{ old('delivery_requested') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="delivery_requested">
                                        I would like my prescription delivered to my home
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3 delivery-address-section" id="deliveryAddressSection">
                                <label for="delivery_address" class="form-label">Delivery Address</label>
                                <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" rows="3">{{ old('delivery_address', Auth::user()->address ?? '') }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="form-section-title">Additional Information</h3>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes or Special Instructions</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="submit-btn">Submit Refill Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deliveryCheckbox = document.getElementById('delivery_requested');
        const deliveryAddressSection = document.getElementById('deliveryAddressSection');
        
        // Set initial state
        deliveryAddressSection.style.display = deliveryCheckbox.checked ? 'block' : 'none';
        
        // Add event listener for changes
        deliveryCheckbox.addEventListener('change', function() {
            deliveryAddressSection.style.display = this.checked ? 'block' : 'none';
        });
    });
</script>
@endsection
