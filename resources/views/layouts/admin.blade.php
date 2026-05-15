<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - MediCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1d3557;
            --secondary-color: #457b9d;
            --accent-color: #a8dadc;
            --background-light: #f1faee;
            --text-color: #1d3557;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
        }
        
        /* Admin Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .admin-sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .admin-sidebar-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .admin-sidebar-menu {
            padding: 0;
            list-style: none;
            margin-top: 20px;
        }
        
        .admin-sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .admin-sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .admin-sidebar-menu a:hover, 
        .admin-sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .admin-sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .admin-sidebar-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 15px 0;
        }
        
        /* Admin Content */
        .admin-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }
        
        .admin-header {
            background-color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-header h1 {
            margin: 0;
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        
        .admin-card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 100%;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }
            
            .admin-sidebar.show {
                margin-left: 0;
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .admin-content.sidebar-open {
                margin-left: var(--sidebar-width);
            }
            
            .sidebar-toggle {
                display: block !important;
            }
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Admin Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-header">
            <h3>MediCare Admin</h3>
        </div>
        
        <ul class="admin-sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <div class="admin-sidebar-divider"></div>
            
            <li>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            
            <div class="admin-sidebar-divider"></div>
            
            <li>
                <a href="{{ url('/') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
            </li>
            
            <li>
                <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    
    <!-- Admin Content -->
    <div class="admin-content" id="adminContent">
        <div class="admin-header">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <h1>@yield('title', 'Admin Dashboard')</h1>
            
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.users.edit', Auth::id()) }}">My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const adminSidebar = document.getElementById('adminSidebar');
            const adminContent = document.getElementById('adminContent');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    adminSidebar.classList.toggle('show');
                    adminContent.classList.toggle('sidebar-open');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>