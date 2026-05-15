@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Our Blog</h1>
    
    <div class="alert alert-info">
        Our blog is currently under construction. Please check back later for health tips, medical news, and more!
    </div>
    
    <!-- Placeholder for future blog posts -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('images/blog-placeholder.jpg') }}" class="card-img-top" alt="Blog Post">
                <div class="card-body">
                    <h5 class="card-title">The Importance of Regular Health Check-ups</h5>
                    <p class="card-text">Coming soon...</p>
                    <p class="text-muted">Posted on: April 1, 2023</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('images/blog-placeholder.jpg') }}" class="card-img-top" alt="Blog Post">
                <div class="card-body">
                    <h5 class="card-title">Understanding Vaccination Schedules</h5>
                    <p class="card-text">Coming soon...</p>
                    <p class="text-muted">Posted on: April 1, 2023</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ asset('images/blog-placeholder.jpg') }}" class="card-img-top" alt="Blog Post">
                <div class="card-body">
                    <h5 class="card-title">Healthy Eating Habits for a Better Life</h5>
                    <p class="card-text">Coming soon...</p>
                    <p class="text-muted">Posted on: April 1, 2023</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

