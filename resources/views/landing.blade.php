<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MediCare') }} - Your Trusted Healthcare Partner</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-color: #1d3557;
            --secondary-color: #457b9d;
            --accent-color: #a8dadc;
            --background-light: #f1faee;
            --text-color: #1d3557;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Figtree', sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
            background-color: var(--background-light);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(29, 53, 87, 0.8), rgba(29, 53, 87, 0.9)), url('https://images.unsplash.com/photo-1631815588090-d1bcbe9a8545?q=80&w=2574&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 150px 0;
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        /* Logo in Top Left of Hero Section */
        .hero-logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 120px;
            height: auto;
            z-index: 100;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .hero-content {
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--primary-color);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: white;
            border-color: white;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-light {
            border-color: white;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: white;
            color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background-color: var(--background-light);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.2rem;
            margin-bottom: 3rem;
            text-align: center;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            color: var(--secondary-color);
        }

        .feature-card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .feature-description {
            color: #666;
            margin-bottom: 0;
        }

        /* Logo in Hero Section Right Side */
        .logo-container {
            position: relative;
            padding: 20px;
        }

        .medicare-logo {
            max-width: 80%;
            height: auto;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.15));
            animation: float 6s ease-in-out infinite, glow 3s ease-in-out infinite alternate;
        }


        @keyframes pulseHeal {
            0% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 0.9; }
        }

        @keyframes healingGlow {
            0% { filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5)); }
            50% { filter: drop-shadow(0 0 20px rgba(168, 218, 220, 0.8)); }
            100% { filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5)); }
        }

        .medicare-logo {
            animation: pulseHeal 3s ease-in-out infinite, healingGlow 4s ease-in-out infinite;
        }


        /* Responsive */
        @media (max-width: 991px) {
            .hero-logo {
                width: 100px;
            }
            .hero-title {
                font-size: 2.8rem;
            }
            .hero-subtitle {
                font-size: 1.3rem;
            }
            .section-title {
                font-size: 2.2rem;
            }
            .medicare-logo {
                max-width: 60%;
                margin: 2rem auto;
            }
        }

        @media (max-width: 767px) {
            .hero-logo {
                width: 80px;
                top: 15px;
                left: 15px;
            }
            .hero-section {
                padding: 100px 0;
            }
            .hero-title {
                font-size: 2.3rem;
            }
            .hero-subtitle {
                font-size: 1.1rem;
            }
            .section-title {
                font-size: 2rem;
            }
            .feature-card {
                margin-bottom: 20px;
            }
            .medicare-logo {
                max-width: 50%;
                margin: 1rem auto;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <!-- Logo in Top Left Corner -->
        <img src="{{ asset('images/medicare-logo.png') }}" alt="Medicare Logo" class="hero-logo">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content" data-aos="fade-right" data-aos-duration="1000">
                    <h1 class="hero-title">Your Health Is Our Top Priority</h1>
                    <p class="hero-subtitle">MediCare provides world-class healthcare services with a team of experienced doctors and state-of-the-art facilities.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Sign up</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="zoom-in" data-aos-duration="1200">
                    <div class="logo-container">
                        <img src="{{ asset('images/doctor.png') }}" alt="Medicare Logo" class="medicare-logo">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Why Choose MediCare?</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">We are committed to providing the highest quality healthcare services with compassion and personalized care.</p>

            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h3 class="feature-title">Expert Doctors</h3>
                        <p class="feature-description">Our team consists of highly qualified and experienced medical professionals dedicated to providing the best care.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h3 class="feature-title">Modern Facilities</h3>
                        <p class="feature-description">We are equipped with the latest medical technology and facilities to ensure accurate diagnosis and effective treatment.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3 class="feature-title">Patient-Centered Care</h3>
                        <p class="feature-description">We prioritize our patients' needs and comfort, providing personalized care and support throughout their healthcare journey.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html>
