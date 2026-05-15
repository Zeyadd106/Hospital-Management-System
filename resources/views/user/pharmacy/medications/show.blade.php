@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.pharmacy.medications.index') }}">Medications</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $medication->name }}</li>
                </ol>
            </nav>
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

    <div class="row">
        <!-- Medication Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            @if($medication->image_path)
                                <img src="{{ asset($medication->image_path) }}" alt="{{ $medication->name }}" class="img-fluid rounded" style="max-height: 200px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 200px;">
                                    <i class="fa fa-pills fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h2 class="mb-3">{{ $medication->name }}</h2>
                            
                            <div class="d-flex flex-wrap mb-3">
                                @if($medication->stock && $medication->stock->sum('current_stock') > 0)
                                    <span class="badge bg-success me-2 mb-2">
                                        <i class="fa fa-check-circle me-1"></i>In Stock
                                    </span>
                                @else
                                    <span class="badge bg-danger me-2 mb-2">
                                        <i class="fa fa-times-circle me-1"></i>Out of Stock
                                    </span>
                                @endif

                                @if($medication->prescription_required)
                                    <span class="badge bg-warning text-dark me-2 mb-2">
                                        <i class="fa fa-file-medical me-1"></i>Prescription Required
                                    </span>
                                @else
                                    <span class="badge bg-info me-2 mb-2">
                                        <i class="fa fa-shopping-basket me-1"></i>No Prescription Needed
                                    </span>
                                @endif

                                <span class="badge bg-primary me-2 mb-2">
                                    <i class="fa fa-tag me-1"></i>{{ ucfirst($medication->category ?? 'General') }}
                                </span>
                            </div>

                            <h4 class="text-primary mb-3">${{ number_format($medication->price, 2) }}</h4>
                            
                            @if(!$medication->prescription_required && $medication->stock && $medication->stock->sum('current_stock') > 0)
                                <form action="{{ route('user.pharmacy.cart.add') }}" method="POST" class="mb-3">
                                    @csrf
                                    <input type="hidden" name="medication_id" value="{{ $medication->id }}">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-auto">
                                            <label for="quantity" class="col-form-label">Quantity:</label>
                                        </div>
                                        <div class="col-auto">
                                            <select name="quantity" id="quantity" class="form-select">
                                                @for($i = 1; $i <= min(10, $medication->stock->sum('current_stock')); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-cart-plus me-2"></i>Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @elseif($medication->prescription_required)
                                <div class="alert alert-warning">
                                    <i class="fa fa-exclamation-triangle me-2"></i>This medication requires a prescription.
                                    @if(Auth::check())
                                        <a href="{{ route('user.pharmacy.prescriptions.index') }}" class="alert-link">View your prescriptions</a>.
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-circle me-2"></i>This medication is currently out of stock.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medication Information Tabs -->
            <div class="card mb-4">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="medicationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="false">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="usage-tab" data-bs-toggle="tab" data-bs-target="#usage" type="button" role="tab" aria-controls="usage" aria-selected="false">Usage & Dosage</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="side-effects-tab" data-bs-toggle="tab" data-bs-target="#side-effects" type="button" role="tab" aria-controls="side-effects" aria-selected="false">Side Effects</button>
                        </li>
                    </ul>
                    <div class="tab-content p-3" id="medicationTabsContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Generic Name:</strong> {{ $medication->generic_name ?? 'N/A' }}</p>
                                    <p><strong>Manufacturer:</strong> {{ $medication->manufacturer }}</p>
                                    <p><strong>Category:</strong> {{ ucfirst($medication->category ?? 'General') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Dosage:</strong> {{ $medication->dosage }}</p>
                                    <p><strong>Form:</strong> {{ $medication->form }}</p>
                                    <p><strong>Requires Prescription:</strong> {{ $medication->prescription_required ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <div class="medication-description">
                                {!! $medication->description ?? '<p class="text-muted">No description available for this medication.</p>' !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="usage" role="tabpanel" aria-labelledby="usage-tab">
                            <div class="medication-usage">
                                {!! $medication->usage_instructions ?? '<p class="text-muted">No usage instructions available for this medication.</p>' !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="side-effects" role="tabpanel" aria-labelledby="side-effects-tab">
                            <div class="medication-side-effects">
                                {!! $medication->side_effects ?? '<p class="text-muted">No side effects information available for this medication.</p>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Stock Information -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Stock Information</h5>
                </div>
                <div class="card-body">
                    @if($medication->stock && $medication->stock->sum('current_stock') > 0)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fa fa-check-circle text-success fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">In Stock</h6>
                                <p class="mb-0 text-muted">{{ $medication->stock->sum('current_stock') }} units available</p>
                            </div>
                        </div>

                        <div class="progress mb-3" style="height: 10px;">
                            @php
                                $totalStock = $medication->stock->sum('current_stock');
                                $totalMinStock = $medication->stock->sum('min_stock');
                                $stockPercentage = $totalMinStock > 0 ? min(100, ($totalStock / $totalMinStock) * 100) : 100;
                                $stockClass = $stockPercentage > 70 ? 'bg-success' : ($stockPercentage > 30 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <div class="progress-bar {{ $stockClass }}" role="progressbar" style="width: {{ $stockPercentage }}%" aria-valuenow="{{ $stockPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="small text-muted mb-3">
                            @if($stockPercentage > 70)
                                Stock levels are good
                            @elseif($stockPercentage > 30)
                                Stock levels are moderate
                            @else
                                Stock levels are low
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Current</th>
                                        <th>Minimum</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medication->stock as $stock)
                                        <tr>
                                            <td>{{ $stock->current_stock }}</td>
                                            <td>{{ $stock->min_stock }}</td>
                                            <td>{{ $stock->updated_at ? $stock->updated_at->format('M d, Y') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fa fa-times-circle text-danger fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Out of Stock</h6>
                                <p class="mb-0 text-muted">This medication is currently unavailable</p>
                            </div>
                        </div>
                        <div class="alert alert-info mb-0">
                            <i class="fa fa-info-circle me-2"></i>We're working to restock this item. Please check back later.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Medications -->
            @if(isset($relatedMedications) && $relatedMedications->count() > 0)
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Related Medications</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($relatedMedications as $relatedMed)
                                <a href="{{ route('user.pharmacy.medications.show', $relatedMed->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($relatedMed->image_path)
                                                <img src="{{ asset($relatedMed->image_path) }}" alt="{{ $relatedMed->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: contain;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 40px; height: 40px;">
                                                    <i class="fa fa-pills text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $relatedMed->name }}</h6>
                                            <small class="text-muted">${{ number_format($relatedMed->price, 2) }}</small>
                                        </div>
                                        <div class="ms-auto">
                                            @if($relatedMed->stock && $relatedMed->stock->sum('current_stock') > 0)
                                                <span class="badge bg-success">In Stock</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
