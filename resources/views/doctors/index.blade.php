@extends('layouts.app')

@section('additional_styles')
<style>
    :root {
        --primary-color: #1d3557;
        --secondary-color: #457b9d;
        --accent-color: #a8dadc;
        --background-light: #f1faee;
        --text-color: #1d3557;
    }
    
    body {
        background-color: var(--background-light);
        font-family: 'Arial', sans-serif;
    }

    .doctors-section {
        padding: 50px 0;
    }
    
    .doctors-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .doctors-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .filter-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    
    .doctor-card {
        background-color: var(--primary-color);
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
        text-align: center;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        padding: 50px;
    }
    
    .doctor-card:hover {
        transform: translateY(-5px);
    }
    
    .doctor-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
    }
    
    .doctor-name {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .doctor-specialization {
        font-size: 1rem;
        color: #03f4fc;
        text-align: center;
    }
    
    .equal-height {
        display: flex;
        flex-wrap: wrap;
    }
    
    .equal-height .col-md-4 {
        display: flex;
        padding: 30px;
    }
</style>
@endsection

@section('content')
<div class="doctors-section">
    <div class="container">
        <div class="doctors-header">
            <h2>Our Medical Team</h2>
            <p>Meet our experienced and dedicated healthcare professionals</p>
        </div>
        
        <div class="filter-section">
            <form action="{{ route('doctors.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="department" class="form-label">Filter by Department</label>
                        <select class="form-select" id="department" name="department" onchange="this.form.submit()">
                            <option value="all">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="specialization" class="form-label">Filter by Specialty</label>
                        <select class="form-select" id="specialization" name="specialization" onchange="this.form.submit()">
                            <option value="all">All Specialties</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}" {{ request('specialization') == $specialty ? 'selected' : '' }}>
                                    {{ $specialty }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Search by Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search doctors..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="row equal-height">
            @forelse($doctors as $doctor)
                <div class="col-md-4">
                    <div class="doctor-card">
                        @if($doctor->image)
                            <img src="{{ asset($doctor->image) }}" alt="{{ $doctor->name }}" class="doctor-image">
                        @elseif($doctor->avatar)
                            <img src="{{ asset($doctor->avatar) }}" alt="{{ $doctor->name }}" class="doctor-image">
                        @else
                            <img src="{{ asset('images/doctor-placeholder.jpg') }}" alt="{{ $doctor->name }}" class="doctor-image">
                        @endif
                        
                        <h3 class="doctor-name">{{ $doctor->name }}</h3>
                        <p class="doctor-specialization">{{ $doctor->specialization ?? $doctor->specialty }}</p>
                        
                        <div class="doctor-actions mt-3">
                            <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-user-md me-1"></i> View Profile
                            </a>
                            @auth
                            <a href="{{ route('chat.index', ['doctor' => $doctor->id]) }}" class="btn btn-outline-light btn-sm mt-2">
                                <i class="fas fa-comments me-1"></i> Chat
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> No doctors are currently available. Please check back later.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
