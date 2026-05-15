@extends('layouts.app')

@section('additional_styles')
<style>
    .page-header {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-7ntXRfbxGGKnHzdViNGjjRu0yfM1H7.png');
        background-size: cover;
        background-position: center;
        padding: 100px 0;
        color: white;
        text-align: center;
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .breadcrumb-item, .breadcrumb-item a {
        color: white;
    }

    .breadcrumb-item.active {
        color: var(--accent-color);
    }

    .services-section {
        padding: 60px 0;
    }

    .service-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        transition: all 0.4s ease;
        z-index: -1;
        opacity: 0;
    }

    .service-card:hover::before {
        height: 100%;
        opacity: 1;
    }

    .service-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(69, 123, 157, 0.1);
        border-radius: 50%;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon {
        background-color: white;
    }

    .service-icon i {
        font-size: 30px;
        color: var(--primary-color);
        transition: all 0.3s ease;
    }

    .service-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--primary-color);
        transition: all 0.3s ease;
    }

    .service-description {
        color: #666;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        flex-grow: 1;
    }

    .service-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }

    .service-btn {
        display: inline-flex;
        align-items: center;
        color: var(--primary-color);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .service-btn i {
        margin-left: 5px;
        transition: transform 0.3s ease;
    }

    .service-card:hover .service-title,
    .service-card:hover .service-btn {
        color: white;
    }

    .service-card:hover .service-description {
        color: rgba(255, 255, 255, 0.8);
    }

    .service-card:hover .service-btn i {
        transform: translateX(5px);
    }

    @media (max-width: 768px) {
        .service-card {
            padding: 20px;
        }
        
        .service-icon {
            width: 60px;
            height: 60px;
        }
        
        .service-icon i {
            font-size: 24px;
        }
        
        .service-title {
            font-size: 18px;
        }
    }
</style>
@endsection

@section('content')
    <!-- Page Header
    <div class="page-header">
        <div class="container">
            <h1>Our Services</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Services</li>
                </ol>
            </nav>
        </div>
    </div> -->

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="row">
                <!-- Emergency Care -->
                <div class="col-md-6 col-lg-4">
                    <a href="https://www.who.int/health-topics/emergency-care#tab=tab_1" class="service-link" target="_blank">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-ambulance"></i>
                            </div>
                            <h3 class="service-title">Emergency Care</h3>
                            <p class="service-description">24/7 emergency medical services with experienced healthcare professionals ready to provide immediate care for critical situations.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div>

                <!-- Heart Care -->
                <div class="col-md-6 col-lg-4">
                    <a href="https://www.nm.org/conditions-and-care-areas/heart-and-vascular" class="service-link" target="_blank">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <h3 class="service-title">Heart Care</h3>
                            <p class="service-description">Comprehensive cardiac care services including diagnosis, treatment, and rehabilitation for heart conditions with state-of-the-art facilities.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div>

                <!-- Eye Care -->
                <div class="col-md-6 col-lg-4">
                    <a href="https://www.mercy.com/health-care-services/ophthalmology-eye-care" class="service-link" target="_blank">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="service-title">Eye Care</h3>
                            <p class="service-description">Advanced ophthalmology services with state-of-the-art equipment for all your eye care needs, from routine check-ups to complex surgeries.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div>

                <!-- Orthopedic Care
                <div class="col-md-6 col-lg-4">
                    <a href="https://orthoinfo.aaos.org/" class="service-link" target="_blank"> -->
                        <!-- <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-bone"></i>
                            </div>
                            <h3 class="service-title">Orthopedic Care</h3>
                            <p class="service-description">Specialized treatment for bone and joint conditions with expert orthopedic surgeons using the latest techniques and technologies.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div> -->

                <!-- Pediatric Care -->
                <!-- <div class="col-md-6 col-lg-4">
                    <a href="https://pediatriccareinc.com/" class="service-link" target="_blank">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-baby"></i>
                            </div> -->
                            <!-- <h3 class="service-title">Pediatric Care</h3>
                            <p class="service-description">Specialized medical care for infants, children, and adolescents in a child-friendly environment with compassionate pediatric specialists.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div> -->

                <!-- Outpatient Clinics -->
                <!-- <div class="col-md-6 col-lg-4">
                    <a href="{{ url('/clinics') }}" class="service-link">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <h3 class="service-title">Outpatient Clinics</h3>
                            <p class="service-description">Convenient outpatient services for various medical needs without hospital admission, providing efficient and accessible healthcare.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div> -->

                <!-- Pharmacy -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ url('/pharmacy') }}" class="service-link">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-pills"></i>
                            </div>
                            <h3 class="service-title">Pharmacy</h3>
                            <p class="service-description">Complete pharmaceutical services with a wide range of medications and professional advice from experienced pharmacists.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div>

                <!-- Vaccination -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ url('/vaccinations') }}" class="service-link">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-syringe"></i>
                            </div>
                            <h3 class="service-title">Vaccination</h3>
                            <p class="service-description">Comprehensive vaccination services for all age groups, including routine immunizations and travel vaccines administered by qualified professionals.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div>

                <!-- Health Screening -->
                <div class="col-md-6 col-lg-4">
                    <a href="{{ url('/health-screenings') }}" class="service-link">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <h3 class="service-title">Health Screening</h3>
                            <p class="service-description">Preventive health screening services to detect potential health issues early and promote overall wellness through comprehensive check-ups.</p>
                            <div class="service-btn">Learn More <i class="fas fa-arrow-right"></i></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Any additional JavaScript can go here
</script>
@endsection
