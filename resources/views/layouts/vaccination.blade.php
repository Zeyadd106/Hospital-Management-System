<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare - Vaccination Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <img src="{{ asset('images/medical-cross.png') }}" alt="MediCare Logo" class="icon me-2">
            <a class="navbar-brand">MediCare</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('departments') }}">Departments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctors') }}">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('appointments') }}">Appointment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                    </li>
                </ul>
                <div class="profile-dropdown ms-3">
                    <i class="fas fa-user-circle profile-icon"></i>
                    <div class="dropdown-content">
                        @auth
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="{{ route('profile') }}"><i class="fa-regular fa-user"></i> Profile</a>
                        @else
                            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i>Login</a>
                            <a href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i>Register</a>
                        @endauth
                    </div>
                </div>
            </div>      
        </div>
    </nav>

    <!-- Vaccination Section -->
    <section class="vaccination-section">
        <div class="container">
            <h2 class="text-center mb-5">Our Vaccination Services</h2>
            <div class="row">
                @foreach($vaccinations as $vaccination)
                <div class="col-md-6 col-lg-4">
                    <div class="vaccination-card">
                        <i class="fas fa-syringe vaccination-icon"></i>
                        <h3 class="vaccination-title">{{ $vaccination->name }}</h3>
                        <p class="vaccination-description">{{ $vaccination->description }}</p>
                        <a href="{{ route('vaccination.details', $vaccination->id) }}" class="btn btn-primary">{{ $vaccination->button_text }}</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

