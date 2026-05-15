<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Medicare Admin Dashboard">
    <meta name="author" content="Medicare">
    <title>Medicare - Admin Dashboard</title>

    <!-- Custom fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        * {
            font-family: Arial, sans-serif;
        }

        :root {
            --primary-color: #1d3557;
            --secondary-color: #457b9d;
            --accent-color: #a8dadc;
            --background-light: #f1faee;
            --text-color: #1d3557;
        }

        body {
            background-color: var(--background-light);
            color: var(--text-color);
        }

        .sidebar {
            background-color: var(--primary-color);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            padding: 1rem;
            z-index: 1030;
        }

        .sidebar .nav-link {
            color: white;
            padding: 0.5rem 1rem;
            margin: 0.2rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--secondary-color);
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .table-custom thead {
            background-color: var(--primary-color);
            color: white;
        }

        .badge-confirmed {
            background-color: #198754;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-cancelled {
            background-color: #dc3545;
        }

        .modal-backdrop.show {
            z-index: 1040;
        }

        .modal {
            z-index: 1050;
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .admin-card {
            background-color: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: var(--primary-color);
            color: white;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @yield('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i>Users
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <i class="fas fa-user-md me-2"></i>Doctors
            </a>
            <a href="{{ route('admin.clinics.index') }}" class="nav-link {{ request()->routeIs('admin.clinics.*') ? 'active' : '' }}">
                <i class="fas fa-clinic-medical me-2"></i>Clinics
            </a>
            <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                <i class="fas fa-hospital me-2"></i>Departments
            </a>
            <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-medical me-2"></i>Services
            </a>
            <a href="{{ route('admin.vaccinations.index') }}" class="nav-link {{ request()->routeIs('admin.vaccinations.*') ? 'active' : '' }}">
                <i class="fas fa-syringe me-2"></i>Vaccinations
            </a>
            <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <i class="fas fa-envelope me-2"></i>Messages
            </a>
            <a href="{{ route('admin.profile.show') }}" class="nav-link {{ request()->routeIs('admin.profile.show') ? 'active' : '' }}">
                <i class="fas fa-user-circle me-2"></i>My Profile
            </a>
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals properly
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                new bootstrap.Modal(modal);
                
                modal.addEventListener('click', function(e) {
                    if(e.target === modal) {
                        bootstrap.Modal.getInstance(modal).hide();
                    }
                });
            });
        });
    </script>
<meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('scripts')
</body>
</html>