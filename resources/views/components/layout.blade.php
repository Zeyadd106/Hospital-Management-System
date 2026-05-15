<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MediCare' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .sidebar-header img {
            width: 80px;
            margin-bottom: 15px;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        
        .sidebar-menu li {
            margin: 10px 0;
        }
        
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover {
            background-color: var(--secondary-color);
        }
        
        .sidebar-menu a.active {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 25px;
        }
        
        /* Profile Section */
        .profile-section {
            margin-top: auto; /* Push profile section to the bottom */
            width: 100%;
        }
        
        .profile-section .dropdown {
            width: 100%;
        }
        
        .profile-section .dropdown-toggle {
            width: 100%;
            text-align: left;
            background-color: var(--secondary-color);
            border: none;
            padding: 12px 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        
        .profile-section .dropdown-toggle:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }
        
        .profile-section .dropdown-menu {
            width: 100%;
            background-color: var(--primary-color);
            border: none;
        }
        
        .profile-section .dropdown-item {
            color: white;
            padding: 10px 15px;
            display: flex;
            align-items: center;
        }
        
        .profile-section .dropdown-item:hover {
            background-color: var(--secondary-color);
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        
        .appointment-form {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        
        .form-control {
            border-radius: 5px;
            border: 1px solid var(--secondary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
        
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-bottom: 20px;
            }
        
            .profile-section {
                order: -1; /* Move profile section above the menu */
                margin-bottom: 20px;
            }
        
            .sidebar-menu {
                margin-top: 20px;
            }
        
            .main-content {
                padding: 20px 15px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/medical-cross.png') }}" alt="MediCare Logo">
            <h3>MediCare</h3>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>Home
            </a></li>
            <li><a href="{{ route('vaccination.index') }}" class="{{ request()->routeIs('vaccination.*') ? 'active' : '' }}">
                <i class="fas fa-syringe"></i>Vaccinations
            </a></li>
            <li><a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>Appointments
            </a></li>
            <li><a href="{{ route('health-screenings.index') }}" class="{{ request()->routeIs('health-screenings.*') ? 'active' : '' }}">
                <i class="fas fa-heartbeat"></i>Health Screenings
            </a></li>
            <li><a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                <i class="fas fa-user-md"></i>Doctors
            </a></li>
            <li><a href="{{ route('departments') }}" class="{{ request()->routeIs('departments*') ? 'active' : '' }}">
                <i class="fas fa-building"></i>Departments
            </a></li>
            <li><a href="{{ route('clinics') }}" class="{{ request()->routeIs('clinics*') ? 'active' : '' }}">
                <i class="fas fa-hospital"></i>Clinics
            </a></li>
            <li><a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>Chat
            </a></li>
            <li><a href="{{ route('pharmacy.index') }}" class="{{ request()->routeIs('pharmacy.*') ? 'active' : '' }}">
                <i class="fas fa-prescription-bottle"></i>Pharmacy
            </a></li>
            <li><a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i>Contact Us
            </a></li>
        </ul>
    
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle w-100" href="#" role="button" 
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Profile
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                    @auth
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </form>
                        </li>
                    @else
                        <li><a class="dropdown-item" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>  

    <!-- Main Content -->
    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
