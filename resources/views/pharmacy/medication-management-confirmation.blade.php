@extends('layouts.app')

@section('additional_styles')
<style>
    .confirmation-section {
        padding: 50px 0;
    }
    
    .confirmation-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        text-align: center;
    }
    
    .confirmation-icon {
        font-size: 80px;
        color: #28a745;
        margin-bottom: 20px;
    }
    
    .confirmation-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
        color: var(--primary-color);
    }
    
    .confirmation-code {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        font-size: 20px;
        font-weight: bold;
        margin: 20px 0;
        letter-spacing: 2px;
    }
    
    .confirmation-details {
        margin-top: 30px;
        text-align: left;
    }
    
    .detail-item {
        margin-bottom: 15px;
    }
    
    .detail-label {
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .service-list {
        list-style-type: none;
        padding-left: 0;
    }
    
    .service-list li {
        padding: 5px 0;
    }
    
    .service-list li:before {
        content: "✓";
        color: #28a745;
        margin-right: 10px;
    }
</style>
@endsection

@section('content')
<div class="confirmation-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="confirmation-card">
                    <div class="confirmation-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    
                    <h2 class="confirmation-title">Medication Management Request Submitted</h2>
                    <p>Thank you for submitting your medication management request. We have received your request and will process it as soon as possible.</p>
                    
                    <div class="confirmation-code">
                        Confirmation Code: {{ $management->confirmation_code ?? 'CODE_NOT_AVAILABLE' }}
                    </div>
                    
                    <p>Please save this confirmation code for future reference. You can use it to check the status of your request.</p>
                    
                    <div class="confirmation-details">
                        <h4>Request Details:</h4>
                        
                        <div class="detail-item">
                            <span class="detail-label">Name:</span> {{ $management->name ?? 'Not provided' }}
                        </div>
                        
                        <div class="detail-item">
                            <span class="detail-label">Email:</span> {{ $management->email ?? 'Not provided' }}
                        </div>
                        
                        @if(isset($management->phone))
                            <div class="detail-item">
                                <span class="detail-label">Phone:</span> {{ $management->phone }}
                            </div>
                        @endif
                        
                        <div class="detail-item">
                            <span class="detail-label">Status:</span> 
                            <span class="badge bg-warning">{{ ucfirst($management->status ?? 'pending') }}</span>
                        </div>
                        
                        @if(isset($management->services) && is_array($management->services))
                            <div class="detail-item">
                                <span class="detail-label">Services Requested:</span>
                                <ul class="service-list mt-2">
                                    @foreach($management->services as $service)
                                        <li>{{ ucwords(str_replace('_', ' ', $service)) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="detail-item">
                            <span class="detail-label">Submitted On:</span> {{ isset($management->created_at) ? $management->created_at->format('F d, Y \a\t h:i A') : date('F d, Y \a\t h:i A') }}
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('pharmacy.index') }}" class="btn btn-primary me-2">Back to Pharmacy</a>
                        @auth
                            <a href="{{ route('pharmacy.my-medication-management') }}" class="btn btn-outline-primary">View My Requests</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

