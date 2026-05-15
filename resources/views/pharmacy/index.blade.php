@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Medications Banner -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-2">Browse Our Medications</h3>
                        <p class="mb-0">Find prescription and over-the-counter medications at competitive prices.</p>
                    </div>
                    <a href="{{ Auth::check() ? route('user.pharmacy.medications.index') : '/pharmacy/medications' }}" class="btn btn-light btn-lg">
                        <i class="fas fa-pills me-2"></i>View Medications
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Find a Pharmacy</h3>
                </div>
                
                <div class="card-body">
                    <div class="search-container mb-4">
                        <form action="{{ route('pharmacy.search') }}" method="GET" class="d-flex">
                            <input type="text" name="query" class="form-control me-2" placeholder="Search pharmacies..." value="{{ request()->query('query') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </form>
                    </div>

                    @if($pharmacies->count() > 0)
                        <div class="row">
                            @foreach($pharmacies as $pharmacy)
                                <div class="col-md-4 mb-4">
                                    <div class="pharmacy-card">
                                        <div class="pharmacy-card-header">
                                            <h4>{{ $pharmacy->name }}</h4>
                                            <span class="pharmacy-status {{ $pharmacy->status ? 'active' : 'inactive' }}">
                                                {{ $pharmacy->status ? 'Open' : 'Closed' }}
                                            </span>
                                        </div>
                                        <div class="pharmacy-card-body">
                                            <p><i class="fas fa-map-marker-alt"></i> {{ $pharmacy->address }}</p>
                                            <p><i class="fas fa-phone"></i> {{ $pharmacy->phone }}</p>
                                            <p><i class="fas fa-envelope"></i> {{ $pharmacy->email }}</p>
                                            <div class="pharmacy-actions mt-3">
                                                <a href="{{ route('pharmacy.show', $pharmacy->id) }}" class="btn btn-primary btn-sm">
                                                    View Details
                                                </a>
                                                <a href="{{ route('pharmacy.searchMedicine') }}?query={{ $pharmacy->name }}" class="btn btn-outline-primary btn-sm">
                                                    Search Medicines
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pharmacies->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No pharmacies found matching your search criteria.</p>
                            <a href="{{ route('pharmacy.index') }}" class="btn btn-primary">
                                Show All Pharmacies
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_styles')
<style>
    .pharmacy-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .pharmacy-card:hover {
        transform: translateY(-5px);
    }

    .pharmacy-card-header {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .pharmacy-card-header h4 {
        margin: 0;
        color: #333;
    }

    .pharmacy-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }

    .pharmacy-status.active {
        background-color: #4CAF50;
        color: white;
    }

    .pharmacy-status.inactive {
        background-color: #f44336;
        color: white;
    }

    .pharmacy-card-body {
        padding: 15px;
    }

    .pharmacy-card-body p {
        margin: 5px 0;
        color: #666;
    }

    .pharmacy-actions {
        display: flex;
        gap: 10px;
    }

    .search-container {
        margin-bottom: 20px;
    }

    .search-container form {
        gap: 10px;
    }
</style>
@endsection
