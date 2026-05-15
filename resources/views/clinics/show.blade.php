@extends('layouts.app')

@section('additional_styles')
<style>
    .clinic-detail-section {
        padding: 50px 0;
    }
    
    .clinic-detail-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .clinic-detail-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .clinic-detail-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .clinic-detail-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    
    .clinic-detail-info {
        padding: 30px;
    }
    
    .clinic-detail-name {
        font-size: 24px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .clinic-detail-department {
        color: var(--secondary-color);
        font-weight: 500;
        margin-bottom: 20px;
    }
    
    .clinic-detail-description {
        margin-bottom: 30px;
        color: #6c757d;
    }
    
    .clinic-doctors-section {
        margin-top: 40px;
    }
    
    .clinic-doctor-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }
    
    .clinic-doctor-card:hover {
        transform: translateY(-5px);
    }
    
    .clinic-doctor-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 20px;
    }
    
    .clinic-doctor-name {
        font-size: 18px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .clinic-doctor-specialization {
        color: var(--secondary-color);
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="clinic-detail-section">
    <div class="container">
        <div class="clinic-detail-header">
            <h2>Clinic Details</h2>
            <p>Learn more about our specialized healthcare facility</p>
        </div>
        
        <div class="clinic-detail-card">
            @if($clinic->image)
                <img src="{{ asset($clinic->image) }}" alt="{{ $clinic->name }}" class="clinic-detail-image">
            @else
                <img src="{{ asset('images/clinic-placeholder.jpg') }}" alt="{{ $clinic->name }}" class="clinic-detail-image">
            @endif
            
            <div class="clinic-detail-info">
                <h3 class="clinic-detail-name">{{ $clinic->name }}</h3>
                
                @if($clinic->department)
                    <p class="clinic-detail-department">
                        <i class="fas fa-hospital-alt me-2"></i> {{ $clinic->department->name }} Department
                    </p>
                @endif
                
                <div class="clinic-detail-description">
                    <p>{{ $clinic->description }}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4 class="card-title">Services Offered</h4>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Consultations</li>
                                    <li class="list-group-item">Diagnostic Tests</li>
                                    <li class="list-group-item">Treatment Procedures</li>
                                    <li class="list-group-item">Follow-up Care</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Working Hours</h4>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Monday - Friday: 8:00 AM - 6:00 PM</li>
                                    <li class="list-group-item">Saturday: 9:00 AM - 1:00 PM</li>
                                    <li class="list-group-item">Sunday: Closed</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('appointments.create', ['id' => $clinic->id]) }}" class="btn btn-primary">
                        <i class="fas fa-calendar-check me-2"></i> Book Appointment
                    </a>
                    <a href="{{ route('contact.index') }}" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-envelope me-2"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
        
        @if(isset($doctors) && $doctors->count() > 0)
            <div class="clinic-doctors-section">
                <h3 class="mb-4">Doctors at this Clinic</h3>
                
                <div class="row">
                    @foreach($doctors as $doctor)
                        <div class="col-md-6">
                            <div class="clinic-doctor-card d-flex">
                                @if($doctor->avatar)
                                    <img src="{{ asset($doctor->avatar) }}" alt="{{ $doctor->name }}" class="clinic-doctor-image">
                                @else
                                    <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->name }}" class="clinic-doctor-image">
                                @endif
                                
                                <div>
                                    <h4 class="clinic-doctor-name">{{ $doctor->name }}</h4>
                                    <p class="clinic-doctor-specialization">{{ $doctor->specialty }}</p>
                                    <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-user-md me-1"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

