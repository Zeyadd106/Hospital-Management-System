@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-pills me-2"></i>Medication Search Results</h1>
            <p class="text-muted">Search query: "{{ $query }}"</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('pharmacy.medications.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Medications
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Search Results</h5>
        </div>
        <div class="card-body">
            @if($medications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Manufacturer</th>
                                <th>Strength</th>
                                <th>Form</th>
                                <th>Current Stock</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medications as $medication)
                                <tr>
                                    <td>{{ $medication->name }}</td>
                                    <td>{{ $medication->manufacturer }}</td>
                                    <td>{{ $medication->dosage }}</td>
                                    <td>{{ $medication->form }}</td>
                                    <td>
                                        @if($medication->stock)
                                            <span class="badge bg-{{ $medication->stock->quantity > 10 ? 'success' : ($medication->stock->quantity > 0 ? 'warning' : 'danger') }}">
                                                {{ $medication->stock->quantity }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">No Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($medication->stock)
                                            ${{ number_format($medication->stock->selling_price, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $medication->id }}">
                                            <i class="fas fa-plus-circle me-1"></i> Add Stock
                                        </button>
                                    </td>
                                </tr>

                                <!-- Add Stock Modal -->
                                <div class="modal fade" id="addStockModal{{ $medication->id }}" tabindex="-1" aria-labelledby="addStockModalLabel{{ $medication->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addStockModalLabel{{ $medication->id }}">Add Stock for {{ $medication->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('pharmacy.add-stock', $medication->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="quantity{{ $medication->id }}" class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" id="quantity{{ $medication->id }}" name="quantity" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="batch_number{{ $medication->id }}" class="form-label">Batch Number</label>
                                                        <input type="text" class="form-control" id="batch_number{{ $medication->id }}" name="batch_number" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="expiry_date{{ $medication->id }}" class="form-label">Expiry Date</label>
                                                        <input type="date" class="form-control" id="expiry_date{{ $medication->id }}" name="expiry_date" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="purchase_price{{ $medication->id }}" class="form-label">Purchase Price ($)</label>
                                                        <input type="number" step="0.01" class="form-control" id="purchase_price{{ $medication->id }}" name="purchase_price" min="0" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="selling_price{{ $medication->id }}" class="form-label">Selling Price ($)</label>
                                                        <input type="number" step="0.01" class="form-control" id="selling_price{{ $medication->id }}" name="selling_price" min="0" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Save Stock</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $medications->withQueryString()->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No medications found matching "{{ $query }}".
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
