@extends('layouts.app')

@section('additional_styles')
<style>
    .refill-details-section {
        padding: 50px 0;
    }
    
    .refill-details-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .refill-header {
        margin-bottom: 30px;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 20px;
    }
    
    .refill-title {
        font-size: 24px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: bold;
    }
    
    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    
    .status-processing {
        background-color: #17a2b8;
        color: white;
    }
    
    .status-ready {
        background-color: #28a745;
        color: white;
    }
    
    .status-completed {
        background-color: #6c757d;
        color: white;
    }
    
    .status-cancelled {
        background-color: #dc3545;
        color: white;
    }
    
    .detail-section {
        margin-bottom: 30px;
    }
    
    .detail-section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
        color: var(--primary-color);
    }
    
    .detail-item {
        margin-bottom: 15px;
    }
    
    .detail-label {
        font-weight: bold;
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<div class="refill-details-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="refill-details-card">
                    <div class="refill-header d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="refill-title">Prescription Refill Request</h2>
                            <p class="text-muted">Confirmation Code: {{ $refill->confirmation_code }}</p>
                        </div>
                        <span class="status-badge status-{{ $refill->status }}">
                            {{ ucfirst($refill->status) }}
                        </span>
                    </div>
                    
                    <div class="detail-section">
                        <h3 class="detail-section-title">Personal Information</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Name</div>
                                    <div>{{ $refill->name }}</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Email</div>
                                    <div>{{ $refill->email }}</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($refill->phone)
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div>{{ $refill->phone }}</div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="detail-section">
                        <h3 class="detail-section-title">Prescription Information</h3>
                        
                        @if($refill->prescription_number)
                            <div class="detail-item">
                                <div class="detail-label">Prescription Number</div>
                                <div>{{ $refill->prescription_number }}</div>
                            </div>
                        @endif
                        
                        <div class="detail-item">
                            <div class="detail-label">Medication Details</div>
                            <div>{{ $refill->medication_details }}</div>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h3 class="detail-section-title">Delivery Information</h3>
                        
                        <div class="detail-item">
                            <div class="detail-label">Delivery Requested</div>
                            <div>{{ $refill->delivery_requested ? 'Yes' : 'No' }}</div>
                        </div>
                        
                        @if($refill->delivery_requested && $refill->delivery_address)
                            <div class="detail-item">
                                <div class="detail-label">Delivery Address</div>
                                <div>{{ $refill->delivery_address }}</div>
                            </div>
                        @endif
                    </div>
                    
                    @if($refill->notes)
                        <div class="detail-section">
                            <h3 class="detail-section-title">Additional Notes</h3>
                            
                            <div class="detail-item">
                                <div>{{ $refill->notes }}</div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="detail-section">
                        <h3 class="detail-section-title">Request Information</h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Submitted On</div>
                                    <div>{{ $refill->created_at->format('F d, Y \a\t h:i A') }}</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label">Last Updated</div>
                                    <div>{{ $refill->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-primary me-2">Back to Pharmacy</a>
                        @auth
                            <a href="{{ route('pharmacy.my-prescription-refills') }}" class="btn btn-outline-primary">View All My Requests</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection