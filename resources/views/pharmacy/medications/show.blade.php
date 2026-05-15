@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Medication Details</h1>
        <div>
            <a href="{{ route('pharmacy.medications.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Medications
            </a>
            <a href="{{ route('pharmacy.medications.edit', $medication) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Medication Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Medication Information</h6>
                    <div>
                        @if($medication->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3">{{ $medication->name }}</h4>
                            <p class="text-muted">{{ $medication->description }}</p>
                            
                            <div class="mb-3">
                                <h6 class="text-primary">Details</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Generic Name:</strong> {{ $medication->generic_name ?? 'N/A' }}</li>
                                    <li><strong>Dosage Form:</strong> {{ $medication->form ?? 'N/A' }}</li>
                                    <li><strong>Strength:</strong> {{ $medication->strength ?? 'N/A' }}</li>
                                    <li><strong>Manufacturer:</strong> {{ $medication->manufacturer ?? 'N/A' }}</li>
                                    <li><strong>Category:</strong> {{ $medication->category ?? 'N/A' }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                @if($medication->image_url)
                                    <img src="{{ asset('storage/' . $medication->image_url) }}" 
                                         alt="{{ $medication->name }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 200px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px; border: 1px dashed #ccc; border-radius: 5px;">
                                        <span class="text-muted">No image available</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-primary">Pricing</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Unit Price:</strong> ${{ number_format($medication->price, 2) }}</li>
                                    <li><strong>Purchase Price:</strong> ${{ number_format($medication->purchase_price ?? 0, 2) }}</li>
                                    <li><strong>Markup:</strong> 
                                        @if($medication->purchase_price && $medication->purchase_price > 0)
                                            {{ number_format((($medication->price - $medication->purchase_price) / $medication->purchase_price) * 100, 2) }}%
                                        @else
                                            N/A
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Stock Information</h6>
                    <div>
                        <a href="{{ route('pharmacy.medications.stock.history', $medication) }}" 
                           class="btn btn-sm btn-outline-primary" 
                           data-bs-toggle="tooltip" 
                           title="View Stock History">
                            <i class="fas fa-history"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body text-center">
                    @if($medication->stock)
                        <!-- Stock Gauge -->
                        <div class="gauge mb-4 mx-auto {{ 
                            $medication->stock->current_stock <= $medication->stock->min_stock * 0.3 ? 'bg-danger' : 
                            ($medication->stock->current_stock <= $medication->stock->min_stock * 0.6 ? 'bg-warning' : 'bg-success') 
                        }}">
                            <div class="gauge-body">
                                <div class="gauge-fill" style="transform: rotate({{ 
                                    min(0.5, max(0, $medication->stock->current_stock / ($medication->stock->min_stock * 2))) 
                                }}turn);"></div>
                                <div class="gauge-cover">
                                    {{ $medication->stock->current_stock }}
                                    <div class="small text-muted">in stock</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Stock Level</span>
                                <span>
                                    {{ $medication->stock->current_stock }} / {{ $medication->stock->min_stock * 2 }}
                                </span>
                            </div>
                            <div class="progress">
                                @php
                                    $percentage = min(100, ($medication->stock->current_stock / ($medication->stock->min_stock * 2)) * 100);
                                @endphp
                                <div class="progress-bar {{ 
                                    $percentage <= 30 ? 'bg-danger' : ($percentage <= 60 ? 'bg-warning' : 'bg-success') 
                                }}" 
                                role="progressbar" 
                                style="width: {{ $percentage }}%" 
                                aria-valuenow="{{ $percentage }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                    {{ round($percentage) }}%
                                </div>
                            </div>
                            <div class="text-muted small mt-1">
                                @if($medication->stock->current_stock <= $medication->stock->min_stock)
                                    <i class="fas fa-exclamation-triangle text-danger"></i> Low stock! Reorder soon.
                                @else
                                    {{ $medication->stock->current_stock - $medication->stock->min_stock }} units above minimum
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <ul class="list-unstyled">
                                <li><strong>Batch Number:</strong> {{ $medication->stock->batch_number ?? 'N/A' }}</li>
                                <li><strong>Expiry Date:</strong> 
                                    @if($medication->stock->expiry_date)
                                        {{ $medication->stock->expiry_date->format('M d, Y') }}
                                        @if($medication->stock->expiry_date->diffInDays(now()) < 30)
                                            <span class="badge bg-warning">Expires soon</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </li>
                                <li><strong>Last Updated:</strong> {{ $medication->stock->updated_at->diffForHumans() }}</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStockModal">
                                <i class="fas fa-plus-circle me-1"></i> Add Stock
                            </button>
                            <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                                <i class="fas fa-adjust me-1"></i> Adjust Stock
                            </button>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            No stock information available for this medication.
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">
                            <i class="fas fa-plus-circle me-1"></i> Add Initial Stock
                        </button>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pharmacy.medications.edit', $medication) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Edit Medication
                        </a>
                        <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#printBarcodeModal">
                            <i class="fas fa-barcode me-1"></i> Print Barcode
                        </a>
                        <a href="{{ route('pharmacy.medications.stock.history', $medication) }}" class="btn btn-outline-info">
                            <i class="fas fa-history me-1"></i> View Stock History
                        </a>
                        @if($medication->is_active)
                            <form action="{{ route('pharmacy.medications.deactivate', $medication) }}" method="POST" class="d-grid">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="fas fa-ban me-1"></i> Deactivate
                                </button>
                            </form>
                        @else
                            <form action="{{ route('pharmacy.medications.activate', $medication) }}" method="POST" class="d-grid">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-check-circle me-1"></i> Activate
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Stock Modals -->
@include('pharmacy.medications.partials.stock-modals', ['medication' => $medication])

@push('styles')
<style>
    .gauge {
        width: 150px;
        height: 150px;
        position: relative;
        margin: 0 auto;
    }
    
    .gauge-body {
        width: 100%;
        height: 100%;
        background: #f5f5f5;
        border-radius: 50%;
        position: relative;
        overflow: hidden;
        border: 8px solid #f1f1f1;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .gauge-fill {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #4e73df;
        transform-origin: center top;
        transform: rotate(0.5turn);
        transition: transform 1s ease-out;
    }
    
    .gauge-cover {
        width: 75%;
        height: 75%;
        background: white;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        box-shadow: 0 0 5px rgba(0,0,0,0.2) inset;
    }
    
    .gauge-cover .small {
        font-size: 0.7rem;
        font-weight: normal;
        color: #6c757d;
    }
    
    .progress {
        height: 1.5rem;
        font-size: 0.8rem;
    }
    
    .progress-bar {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Set minimum date for expiry date fields to today
        document.querySelectorAll('input[type="date"]').forEach(field => {
            if (!field.value) {
                field.min = new Date().toISOString().split('T')[0];
            }
        });

        // Handle adjustment type change
        const adjustmentType = document.getElementById('adjustmentType');
        const amountLabel = document.querySelector('label[for="amount"]');
        
        if (adjustmentType && amountLabel) {
            adjustmentType.addEventListener('change', function() {
                const type = this.value;
                let labelText = 'Amount';
                
                switch(type) {
                    case 'add':
                        labelText = 'Amount to Add';
                        break;
                    case 'remove':
                        labelText = 'Amount to Remove';
                        break;
                    case 'set':
                        labelText = 'New Quantity';
                        break;
                }
                
                amountLabel.textContent = labelText;
            });
        }
    });
</script>
@endpush

<!-- Toast Container -->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100"></div>

@endsection
