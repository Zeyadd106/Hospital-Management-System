@extends('layouts.app')

@section('content')
<section class="health-section">
    <div class="container">
        <h2 class="text-center mb-5">Our Health Screenings</h2>
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <i class="fas fa-heartbeat card-icon"></i>
                    <h3 class="card-title">Blood Pressure Screening</h3>
                    <p class="card-text">Monitor your blood pressure and detect early signs of hypertension.</p>
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <i class="fas fa-vial card-icon"></i>
                    <h3 class="card-title">Cholesterol Testing</h3>
                    <p class="card-text">Track your cholesterol levels for better heart health.</p>
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <i class="fas fa-eye card-icon"></i>
                    <h3 class="card-title">Vision & Hearing Tests</h3>
                    <p class="card-text">Ensure your vision and hearing are in top condition.</p>
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <i class="fas fa-burn card-icon"></i>
                    <h3 class="card-title">Diabetes Screening</h3>
                    <p class="card-text">Get tested for diabetes and manage your health effectively.</p>
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <i class="fas fa-lungs card-icon"></i>
                    <h3 class="card-title">Lung Function Test</h3>
                    <p class="card-text">Check for potential respiratory issues with a lung function test.</p>
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-center">
                    <i class="fas fa-flask card-icon"></i>
                    <h3 class="card-title">Cancer Screening</h3>
                    <p class="card-text">Early detection through cancer screening saves lives.</p>
                    <a href="#" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection