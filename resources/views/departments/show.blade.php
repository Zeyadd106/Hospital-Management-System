@extends('layouts.app')

@section('additional_styles')
<style>
    .department-header {
        background-color: var(--primary-color);
        color: white;
        padding: 60px 0;
        text-align: center;
        margin-bottom: 40px;
    }
    
    .department-header h1 {
        font-size: 2.5rem;
        font-weight: bold;
    }
    
    .department-content {
        padding: 30px 0;
    }
    
    .department-description {
        margin-bottom: 40px;
    }
    
    .department-doctors {
        margin-top: 40px;
    }
    
    .doctor-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
        display: flex;
        align-items: center;
    }
    
    .doctor-card:hover {
        transform: translateY(-5px);
    }
    
    .doctor-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 20px;
    }
    
    .doctor-info h4 {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .doctor-info p {
        margin-bottom: 5px;
        color: #666;
    }
    
    .doctor-actions {
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="department-header">
    <div class="container">
        <h1>{{ $department->name }}</h1>
    </div>
</div>

<div class="department-content">
    <div class="container">
        <div class="department-description">
            <h2 class="mb-4">About {{ $department->name }}</h2>
            <p>{{ $department->description }}</p>
        </div>
        
        <div class="department-doctors">
            <h2 class="mb-4">Our {{ $department->name }} Specialists</h2>
            
            @if($doctors->count() > 0)
                <div class="row">
                    @foreach($doctors as $doctor)
                        <div class="col-md-6">
                            <div class="doctor-card">
                                @if($doctor->image)
                                    <img src="{{ asset($doctor->image) }}" alt="{{ $doctor->name }}" class="doctor-image">
                                @elseif($doctor->avatar)
                                    <img src="{{ asset($doctor->avatar) }}" alt="{{ $doctor->name }}" class="doctor-image">
                                @else
                                    <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->name }}" class="doctor-image">
                                @endif
                                
                                <div class="doctor-info">
                                    <h4>{{ $doctor->name }}</h4>
                                    <p><strong>Specialty:</strong> {{ $doctor->specialization ?? $doctor->specialty }}</p>
                                    <p><strong>Experience:</strong> {{ $doctor->experience ?? 'N/A' }} years</p>
                                    
                                    <div class="doctor-actions">
                                        <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-user-md me-1"></i> View Profile
                                        </a>
                                        <a href="{{ route('doctors.book', $doctor->id) }}" class="btn btn-sm btn-primary ms-2">
                                            <i class="fas fa-calendar-check me-1"></i> Book Appointment
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No doctors are currently available in this department. Please check back later.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
