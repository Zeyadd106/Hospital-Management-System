@extends('layouts.app')

@section('additional_styles')
<style>
    .medication-management-section {
        padding: 50px 0;
    }
    
    .medication-management-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .medication-management-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .medication-management-form {
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
    
    .service-option {
        margin-bottom: 15px;
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
<div class="medication-management-section">
    <div class="container">
        <div class="medication-management-header">
            <h2>Medication Management Services</h2>
            <p>Let us help you manage your medications effectively and safely.</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="medication-management-form">
                    <form action="{{ route('pharmacy.medication-management.store') }}" method="POST">
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
                            <h3 class="form-section-title">Current Medications</h3>
                            
                            <div class="mb-3">
                                <label for="medications" class="form-label">List Your Current Medications</label>
                                <textarea class="form-control @error('medications') is-invalid @enderror" id="medications" name="medications" rows="4" placeholder="Please list all medications you are currently taking, including dosage and frequency.">{{ old('medications') }}</textarea>
                                @error('medications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="form-section-title">Services Requested</h3>
                            
                            <div class="service-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="service_medication_review" name="services[]" value="medication_review" {{ (is_array(old('services')) && in_array('medication_review', old('services'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="service_medication_review">
                                        <strong>Medication Review</strong> - A comprehensive review of all your medications to identify potential interactions or issues.
                                    </label>
                                </div>
                            </div>
                            
                            <div class="service-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="service_pill_packaging" name="services[]" value="pill_packaging" {{ (is_array(old('services')) && in_array('pill_packaging', old('services'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="service_pill_packaging">
                                        <strong>Pill Packaging</strong> - Custom packaging of your medications organized by day and time.
                                    </label>
                                </div>
                            </div>
                            
                            <div class="service-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="service_medication_synchronization" name="services[]" value="medication_synchronization" {{ (is_array(old('services')) && in_array('medication_synchronization', old('services'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="service_medication_synchronization">
                                        <strong>Medication Synchronization</strong> - Coordinate refill dates so all your medications are ready for pickup on the same day.
                                    </label>
                                </div>
                            </div>
                            
                            <div class="service-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="service_medication_counseling" name="services[]" value="medication_counseling" {{ (is_array(old('services')) && in_array('medication_counseling', old('services'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="service_medication_counseling">
                                        <strong>Medication Counseling</strong> - One-on-one consultation with a pharmacist about your medications.
                                    </label>
                                </div>
                            </div>
                            
                            @error('services')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-section">
                            <h3 class="form-section-title">Additional Information</h3>
                            
                            <div class="mb-3">
                                <label for="special_requests" class="form-label">Special Requests or Questions</label>
                                <textarea class="form-control @error('special_requests') is-invalid @enderror" id="special_requests" name="special_requests" rows="3">{{ old('special_requests') }}</textarea>
                                @error('special_requests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="submit-btn">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

