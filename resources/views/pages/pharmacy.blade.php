@extends('layouts.app')

@section('additional_styles')
<style>
    .pharmacy-section {
        padding: 80px 0;
        background-color: var(--background-light);
    }

    .pharmacy-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .pharmacy-header h2 {
        font-size: 32px;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .pharmacy-header p {
        font-size: 18px;
        color: var(--text-color);
        max-width: 800px;
        margin: 0 auto;
    }

    .pharmacy-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
        height: 100%;
    }

    .pharmacy-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .pharmacy-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .pharmacy-card h3 {
        font-size: 24px;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .pharmacy-card p {
        font-size: 16px;
        color: var(--text-color);
        margin-bottom: 20px;
    }

    .pharmacy-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .pharmacy-btn:hover {
        background-color: var(--secondary-color);
        color: white;
    }
</style>
@endsection

@section('content')
<section class="pharmacy-section">
    <div class="container">
        <div class="pharmacy-header">
            <h2>Our Pharmacy Services</h2>
            <p>MediCare offers a comprehensive range of pharmacy services to meet all your medication needs. Our experienced pharmacists are available to provide expert advice and ensure you receive the right medications.</p>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="pharmacy-card">
                    <div class="pharmacy-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h3>Prescription Medications</h3>
                    <p>We offer a wide range of prescription medications with quick processing times and competitive prices.</p>
                    <a href="#" class="pharmacy-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="pharmacy-card">
                    <div class="pharmacy-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Medication Delivery</h3>
                    <p>Get your medications delivered directly to your doorstep with our convenient delivery service.</p>
                    <a href="#" class="pharmacy-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="pharmacy-card">
                    <div class="pharmacy-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Medication Consultation</h3>
                    <p>Our experienced pharmacists provide personalized consultations to help you understand your medications and their proper use.</p>
                    <a href="#" class="pharmacy-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="pharmacy-card">
                    <div class="pharmacy-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Health Monitoring</h3>
                    <p>We offer blood pressure monitoring, glucose testing, and other health monitoring services to help manage your conditions.</p>
                    <a href="#" class="pharmacy-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="pharmacy-card">
                    <div class="pharmacy-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Medication Reminders</h3>
                    <p>Never miss a dose with our medication reminder service, helping you stay on track with your treatment plan.</p>
                    <a href="#" class="pharmacy-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="pharmacy-card">
                    <div class="pharmacy-icon">
                        <i class="fas fa-capsules"></i>
                    </div>
                    <h3>Over-the-Counter Products</h3>
                    <p>Browse our selection of over-the-counter medications, vitamins, and health supplements for your wellness needs.</p>
                    <a href="#" class="pharmacy-btn">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

