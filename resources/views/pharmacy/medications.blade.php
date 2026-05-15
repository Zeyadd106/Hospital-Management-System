@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar with filters -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter Options</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/pharmacy/medications') }}" method="GET" id="filter-form">
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $category)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="d-flex">
                                <input type="number" name="min_price" class="form-control me-2" placeholder="Min" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <!-- Availability Filter -->
                        <div class="mb-3">
                            <label class="form-label">Availability</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}>
                                <label class="form-check-label" for="in_stock">In Stock Only</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                        <a href="{{ url('/pharmacy/medications') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-undo me-2"></i>Reset Filters
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Medication Listings -->
        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Available Medications</h4>
                    <a href="{{ url('/pharmacy/cart') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>View Cart
                        <span class="badge bg-light text-dark ms-2" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                    </a>
                </div>
                <div class="card-body">
                    <div class="search-container mb-4">
                        <form action="{{ url('/pharmacy/medications') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search medications by name or description" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($medications->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>No medications available matching your criteria.
                        </div>
                    @else
                        <div class="row">
                            @foreach($medications as $medication)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        @if($medication->image_path)
                                            <img src="{{ asset($medication->image_path) }}" class="card-img-top p-3" alt="{{ $medication->name }}" style="height: 200px; object-fit: contain;">
                                        @else
                                            <div class="text-center p-3 bg-light">
                                                <i class="fas fa-pills fa-4x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $medication->name }}</h5>
                                            <p class="card-text">
                                                <strong>Category:</strong> {{ ucfirst(str_replace('_', ' ', $medication->category)) }}<br>
                                                <strong>Price:</strong> ${{ number_format($medication->price, 2) }}
                                            </p>
                                            
                                            @if($medication->stock && $medication->stock->quantity > 0)
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-success me-2">
                                                        <i class="fas fa-check-circle me-1"></i>In Stock
                                                    </span>
                                                    <span class="text-muted small">{{ $medication->stock->quantity }} units available</span>
                                                </div>
                                            @else
                                                <span class="badge bg-danger mb-2">
                                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                                </span>
                                            @endif
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ url('/pharmacy/medications/' . $medication->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-info-circle me-1"></i>Details
                                                </a>
                                                @if($medication->stock && $medication->stock->quantity > 0)
                                                    <form action="{{ url('/pharmacy/cart/add') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="medication_id" value="{{ $medication->id }}">
                                                        <button type="submit" class="btn btn-sm btn-success add-to-cart-btn">
                                                            <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="fas fa-times-circle me-1"></i>Out of Stock
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $medications->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle category change
        document.getElementById('category').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });

        // Handle in-stock checkbox
        document.getElementById('in_stock').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });

        // Add to cart animation
        document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                // Prevent default form submission
                e.preventDefault();
                
                // Get the form
                const form = this.closest('form');
                
                // Animate button
                this.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                this.classList.remove('btn-success');
                this.classList.add('btn-primary');
                
                // Update cart count
                const cartCount = document.getElementById('cart-count');
                cartCount.textContent = parseInt(cartCount.textContent) + 1;
                
                // Submit the form after animation
                setTimeout(function() {
                    form.submit();
                }, 500);
            });
        });
    });
</script>
@endsection
