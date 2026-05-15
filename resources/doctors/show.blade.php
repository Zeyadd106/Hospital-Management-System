@extends('layouts.app')

@section('additional_styles')
<style>
    .doctor-profile-section {
        padding: 50px 0;
    }
    
    .doctor-profile-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .doctor-profile-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .doctor-profile-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .doctor-profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
    }
    
    .doctor-profile-info {
        padding: 30px;
    }
    
    .doctor-profile-name {
        font-size: 24px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .doctor-profile-specialization {
        color: var(--secondary-color);
        font-weight: 500;
        margin-bottom: 20px;
    }
    
    .doctor-profile-detail {
        margin-bottom: 15px;
    }
    
    .doctor-profile-detail i {
        color: var(--primary-color);
        width: 20px;
        margin-right: 10px;
    }
    
    .doctor-profile-bio {
        margin-top: 30px;
    }
    
    .doctor-profile-actions {
        margin-top: 30px;
    }
    
    .schedule-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }
    
    .schedule-title {
        font-size: 18px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="doctor-profile-section">
    <div class="container">
        <div class="doctor-profile-header">
            <h2>Doctor Profile</h2>
            <p>Learn more about our healthcare professional</p>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="doctor-profile-card">
                    <div class="doctor-profile-info">
                        <div class="text-center mb-4">
                            @if($doctor->image)
                                <img src="{{ asset($doctor->image) }}" alt="{{ $doctor->name }}" class="doctor-profile-image">
                            @else
                                <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->name }}" class="doctor-profile-image">
                            @endif
                            
                            <h3 class="doctor-profile-name">{{ $doctor->name }}</h3>
                            <p class="doctor-profile-specialization">{{ $doctor->specialization }}</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="doctor-profile-detail">
                                    <i class="fas fa-hospital"></i> <strong>Department:</strong> {{ $doctor->department }}
                                </div>
                                
                                <div class="doctor-profile-detail">
                                    <i class="fas fa-envelope"></i> <strong>Email:</strong> {{ $doctor->email }}
                                </div>
                                
                                <div class="doctor-profile-detail">
                                    <i class="fas fa-phone"></i> <strong>Phone:</strong> {{ $doctor->phone }}
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="doctor-profile-detail">
                                    <i class="fas fa-clock"></i> <strong>Working Hours:</strong> {{ $doctor->working_hours }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="doctor-profile-bio">
                            <h4>About {{ $doctor->name }}</h4>
                            <p>{{ $doctor->bio }}</p>
                        </div>
                        
                        <div class="doctor-profile-actions">
                            <a href="{{ route('doctors.book', $doctor->id) }}" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i> Book Appointment
                            </a>
                            <a href="{{ route('chat.index', ['doctor' => $doctor->id]) }}" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-comments me-2"></i> Chat with Doctor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="schedule-card">
                    <h4 class="schedule-title">Working Schedule</h4>
                    <p>{{ $doctor->working_hours }}</p>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i> Book an appointment to see available time slots.
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="schedule-title">Contact Information</h4>
                        
                        <div class="mb-3">
                            <i class="fas fa-envelope me-2 text-primary"></i> {{ $doctor->email }}
                        </div>
                        
                        <div class="mb-3">
                            <i class="fas fa-phone me-2 text-primary"></i> {{ $doctor->phone }}
                        </div>
                        
                        <a href="{{ route('contact.index', ['doctor' => $doctor->id]) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

