@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Medication Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $medication->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            @if($medication->image_path)
                                <img src="{{ asset($medication->image_path) }}" class="img-fluid rounded" alt="{{ $medication->name }}">
                            @else
                                <div class="text-center p-5 bg-light rounded">
                                    <i class="fas fa-pills fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5>Details</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Category</th>
                                    <td>{{ ucfirst(str_replace('_', ' ', $medication->category)) }}</td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>${{ number_format($medication->price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Availability</th>
                                    <td>
                                        @if($medication->stock && $medication->stock->quantity > 0)
                                            <span class="badge bg-success">In Stock ({{ $medication->stock->quantity }} available)</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($medication->manufacturer)
                                <tr>
                                    <th>Manufacturer</th>
                                    <td>{{ $medication->manufacturer }}</td>
                                </tr>
                                @endif
                                @if($medication->dosage)
                                <tr>
                                    <th>Dosage</th>
                                    <td>{{ $medication->dosage }}</td>
                                </tr>
                                @endif
                                @if($medication->prescription_required)
                                <tr>
                                    <th>Prescription</th>
                                    <td>
                                        <span class="badge bg-warning text-dark">Prescription Required</span>
                                    </td>
                                </tr>
                                @endif
                            </table>

                            @if($medication->stock && $medication->stock->quantity > 0)
                                <form action="{{ route('pharmacy.cart.add') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="medication_id" value="{{ $medication->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="quantity">Quantity</label>
                                                <input type="number" name="quantity" id="quantity" class="form-control" 
                                                       value="1" min="1" max="{{ min(10, $medication->stock->quantity) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>This medication is currently out of stock.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Description</h5>
                        <p>{{ $medication->description }}</p>
                    </div>

                    @if($medication->side_effects)
                    <div class="mt-4">
                        <h5>Side Effects</h5>
                        <p>{{ $medication->side_effects }}</p>
                    </div>
                    @endif

                    @if($medication->contraindications)
                    <div class="mt-4">
                        <h5>Contraindications</h5>
                        <p>{{ $medication->contraindications }}</p>
                    </div>
                    @endif

                    @if($medication->storage_instructions)
                    <div class="mt-4">
                        <h5>Storage Instructions</h5>
                        <p>{{ $medication->storage_instructions }}</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('pharmacy.medications') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Medications
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Medications -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Related Medications</h5>
                </div>
                <div class="card-body">
                    @if($relatedMedications->count() > 0)
                        @foreach($relatedMedications as $relatedMed)
                            <div class="related-medication mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        @if($relatedMed->image_path)
                                            <img src="{{ asset($relatedMed->image_path) }}" alt="{{ $relatedMed->name }}" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: contain;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-pills fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $relatedMed->name }}</h6>
                                        <p class="mb-1">${{ number_format($relatedMed->price, 2) }}</p>
                                        <a href="{{ route('pharmacy.medications.show', $relatedMed->id) }}" class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No related medications found.</p>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Need Help?</h5>
                </div>
                <div class="card-body">
                    <p>If you have questions about this medication, please contact our pharmacy team:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> (123) 456-7890</li>
                        <li><i class="fas fa-envelope me-2"></i> pharmacy@example.com</li>
                    </ul>
                    <a href="{{ route('pharmacy.prescription-refill') }}" class="btn btn-outline-primary w-100 mt-3">
                        <i class="fas fa-prescription me-2"></i>Request Prescription Refill
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
