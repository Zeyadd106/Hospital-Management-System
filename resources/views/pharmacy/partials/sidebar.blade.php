<!-- Pharmacy Sidebar -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <h4 class="text-white">Pharmacy</h4>
            <hr class="bg-light">
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('pharmacy.dashboard') ? 'active' : '' }}" 
                   href="{{ route('pharmacy.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('pharmacy.medications.*') ? 'active' : '' }}" 
                   href="{{ route('pharmacy.medications.index') }}">
                    <i class="fas fa-pills me-2"></i>
                    All Medications
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('pharmacy.medications.create') ? 'active' : '' }}" 
                   href="{{ route('pharmacy.medications.create') }}">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add New Medication
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('pharmacy.prescription-refills.*') ? 'active' : '' }}" 
                   href="{{ route('pharmacy.prescription-refills.index') }}">
                    <i class="fas fa-prescription-bottle-alt me-2"></i>
                    Prescription Refills
                    @if(isset($pendingRefills) && $pendingRefills > 0)
                        <span class="badge bg-danger rounded-pill ms-2">{{ $pendingRefills }}</span>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('pharmacy.reports.*') ? 'active' : '' }}" 
                   href="{{ route('pharmacy.reports.index') }}">
                    <i class="fas fa-chart-bar me-2"></i>
                    Reports
                </a>
            </li>
        </ul>
        
        <div class="position-absolute bottom-0 start-0 w-100 p-3">
            <hr class="bg-light">
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light w-100">
                    <i class="fas fa-home me-1"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</nav>

@push('styles')
<style>
    .sidebar {
        min-height: calc(100vh - 56px);
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    
    .sidebar .nav-link {
        color: #e9ecef;
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
        margin: 0.25rem 0.5rem;
        transition: all 0.3s;
    }
    
    .sidebar .nav-link:hover {
        background-color: rgba(255,255,255,0.1);
        color: #fff;
    }
    
    .sidebar .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }
    
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
    
    @media (max-width: 767.98px) {
        .sidebar {
            min-height: auto;
            position: static;
        }
    }
</style>
@endpush
