@extends('layouts.app')

@section('additional_styles')
<style>
    .services-section {
        padding: 80px 0;
        background-color: var(--background-light);
    }

    .services-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .services-header h2 {
        font-size: 32px;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .services-header p {
        font-size: 18px;
        color: var(--text-color);
        max-width: 800px;
        margin: 0 auto;
    }

    .service-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .service-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 20px;
    }

    .service-card h3 {
        font-size: 24px;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .service-card p {
        font-size: 16px;
        color: var(--text-color);
        margin-bottom: 20px;
    }

    .service-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .service-btn:hover {
        background-color: var(--secondary-color);
        color: white;
    }
</style>
@endsection

@section('content')
<section class="services-section">
    <div class="container">
        <div class="services-header">
            <h2>Our Medical Services</h2>
            <p>MediCare offers a comprehensive range of medical services to meet all your healthcare needs. Our experienced team of healthcare professionals is dedicated to providing you with the highest quality care.</p>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h3>General Check-ups</h3>
                    <p>Regular health check-ups to monitor your overall health and detect potential issues early.</p>
                    <a href="#" class="service-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h3>Cardiology</h3>
                    <p>Comprehensive heart care services including diagnostics, treatment, and preventive care.</p>
                    <a href="#" class="service-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Neurology</h3>
                    <p>Specialized care for conditions affecting the brain, spinal cord, and nervous system.</p>
                    <a href="#" class="service-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-bone"></i>
                    </div>
                    <h3>Orthopedics</h3>
                    <p>Treatment for musculoskeletal issues including joints, bones, ligaments, and muscles.</p>
                    <a href="#" class="service-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Ophthalmology</h3>
                    <p>Comprehensive eye care services including vision tests, treatments, and surgeries.</p>
                    <a href="#" class="service-btn">Learn More</a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-baby"></i>
                    </div>
                    <h3>Pediatrics</h3>
                    <p>Specialized healthcare for infants, children, and adolescents up to 18 years of age.</p>
                    <a href="#" class="service-btn">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

