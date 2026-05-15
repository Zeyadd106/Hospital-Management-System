<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MediCare') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Styles -->
    <style>
        :root {
            --primary-color: #1d3557;
            --secondary-color: #457b9d;
            --accent-color: #a8dadc;
            --background-light: #f1faee;
            --text-color: #1d3557;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-light);
            color: var(--text-color);
        }

        nav.navbar {
            padding: 15px;
            background-color: var(--primary-color) !important;
        }

        .navbar .icon {
            width: 40px;
            height: 40px;
        }

        nav.navbar .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        nav.navbar .navbar-nav .nav-link {
            font-size: 18px;
            color: white;
        }

        nav.navbar .navbar-nav .nav-link:hover {
            color: var(--accent-color);
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-icon {
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .profile-icon:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 1;
        }

        .dropdown-content a {
            color: var(--primary-color);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .dropdown-content a:hover {
            background-color: var(--background-light);
        }

        .profile-dropdown:hover .dropdown-content {
            display: block;
        }

        /* Navbar dropdown menu */
        .navbar .dropdown-menu {
            background-color: var(--primary-color);
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .navbar .dropdown-item {
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .navbar .dropdown-item:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .navbar .dropdown-toggle::after {
            vertical-align: middle;
        }
    </style>
    @yield('additional_styles')
</head>

<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="fas fa-plus-square icon me-2"></i>
                MediCare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    
                    <!-- Services Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Request::is('services*') || Request::is('vaccinations*') || Request::is('health-screenings*') ? 'active' : '' }}" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Medical Services
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <li><a class="dropdown-item" href="{{ url('/vaccinations/book') }}">Book Vaccination</a></li>
                            <li><a class="dropdown-item" href="{{ url('/health-screenings/book') }}">Book Health Screening</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('pharmacy*') ? 'active' : '' }}" href="{{ url('/pharmacy') }}">Pharmacy</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('contact*') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact Us</a>
                    </li>
                    
                    <!-- Medical Staff Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ Request::is('doctors*') || Request::is('departments*') || Request::is('clinics*') ? 'active' : '' }}" href="#" id="medicalStaffDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Medical Staff
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="medicalStaffDropdown">
                            <li><a class="dropdown-item" href="{{ route('doctors.index') }}">Doctors</a></li>
                            <li><a class="dropdown-item" href="{{ route('departments') }}">Departments</a></li>
                            <li><a class="dropdown-item" href="{{ route('clinics') }}">Clinics</a></li>
                        </ul>
                    </li>
                </ul>
                
                <!-- User Authentication Links -->
                <div class="ms-auto">
                    @auth
                        <div class="profile-dropdown">
                            <div class="profile-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="dropdown-content">
                                <a href="{{ url('/profile') }}"><i class="fa-regular fa-user me-2"></i>Profile</a>
                                <a href="{{ url('/dashboard') }}"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a>
                                <a href="{{ url('/notifications') }}"><i class="fa-solid fa-bell me-2"></i>Notifications</a>
                                <a href="{{ url('/payments') }}"><i class="fa-solid fa-credit-card me-2"></i>Payments</a>
                                <a href="{{ url('/messages') }}"><i class="fa-solid fa-comments me-2"></i>Messages</a>
                                <a href="{{ url('/blog') }}"><i class="fa-solid fa-blog me-2"></i>Blog</a>
                                @if(Auth::user()->hasRole('doctor'))
                                    <a href="{{ route('doctor.dashboard') }}"><i class="fa-solid fa-user-md me-2"></i>Doctor Dashboard</a>
                                @endif
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-light">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    @if (isset($header))
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    @endif

    <!-- Main Content -->
    <main>
        <div class="container mt-4">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>About MediCare</h5>
                <p>MediCare is your trusted healthcare partner, providing comprehensive medical services and support.</p>
            </div>
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ url('/') }}" class="text-white">Home</a></li>
                    <li><a href="{{ url('/services') }}" class="text-white">Services</a></li>
                    <li><a href="{{ url('/contact') }}" class="text-white">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <p>Email: info@medicare.com<br>
                Phone: (555) 123-4567</p>
            </div>
        </div>
        <hr class="mt-4 mb-4">
        <div class="text-center">
            <p>&copy; {{ date('Y') }} MediCare. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
