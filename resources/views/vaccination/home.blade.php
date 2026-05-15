@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Your Health Is Our Priority</h1>
            <p class="lead mb-4">Get vaccinated today to protect yourself and your loved ones. Our expert medical team provides safe and effective vaccination services.</p>
            <a href="{{ url('/vaccinations') }}" class="btn btn-primary btn-lg">View Vaccinations</a>
        </div>
        <div class="col-lg-6">
            <img src="/placeholder.svg?height=400&width=600" alt="Vaccination Services" class="img-fluid rounded">
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12 text-center mb-4">
            <h2>Our Vaccination Services</h2>
            <p class="lead">Protect yourself and your family with our comprehensive vaccination options</p>
        </div>
    </div>
    
    <div class="row">
        @foreach ($featuredVaccines as $vaccine)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="vaccination-card">
                    <i class="fas fa-syringe vaccination-icon"></i>
                    <h3 class="vaccination-title">{{ $vaccine->name }}</h3>
                    <p class="vaccination-description">{{ Str::limit($vaccine->description, 100) }}</p>
                    <a href="{{ url('/vaccination/' . $vaccine->id) }}" class="btn btn-primary">Learn More</a>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="row mt-5">
        <div class="col-12 text-center">
            <a href="{{ url('/vaccinations') }}" class="btn btn-outline-primary btn-lg">View All Vaccinations</a>
        </div>
    </div>
    
    <div class="row mt-5 py-5 bg-light rounded">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="/placeholder.svg?height=400&width=600" alt="Why Vaccinate" class="img-fluid rounded">
        </div>
        <div class="col-lg-6">
            <h2 class="mb-4">Why Vaccination Is Important</h2>
            <div class="d-flex mb-3">
                <div class="me-3">
                    <i class="fas fa-shield-virus fs-1 text-primary"></i>
                </div>
                <div>
                    <h4>Protection</h4>
                    <p>Vaccines protect against serious diseases and prevent complications.</p>
                </div>
            </div>
            <div class="d-flex mb-3">
                <div class="me-3">
                    <i class="fas fa-users fs-1 text-primary"></i>
                </div>
                <div>
                    <h4>Community Immunity</h4>
                    <p>When enough people are vaccinated, it helps protect the entire community.</p>
                </div>
            </div>
            <div class="d-flex mb-3">
                <div class="me-3">
                    <i class="fas fa-heart fs-1 text-primary"></i>
                </div>
                <div>
                    <h4>Safe and Effective</h4>
                    <p>Vaccines undergo rigorous testing to ensure they are safe and effective.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

