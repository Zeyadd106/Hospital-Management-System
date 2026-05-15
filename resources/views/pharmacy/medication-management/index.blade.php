@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Medication Management</h5>
                    <a href="{{ route('pharmacy.medications.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Medicine
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Low Stock Medicines</h5>
                                    <h3 class="card-text">{{ $lowStock }}</h3>
                                    <small class="text-white-50">Medicines with quantity < 10</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Expired Medicines</h5>
                                    <h3 class="card-text">{{ $expiredMedicines }}</h3>
                                    <small class="text-white-50">Medicines past expiry date</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Medicines</h5>
                                    <h3 class="card-text">{{ $medicines->total() }}</h3>
                                    <small class="text-white-50">In stock</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Batch #</th>
                                    <th>Quantity</th>
                                    <th>Expiry Date</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medicines as $medicine)
                                    <tr>
                                        <td>
                                            <strong>{{ $medicine->name }}</strong>
                                            @if($medicine->quantity < 10)
                                                <span class="badge bg-warning text-dark">Low Stock</span>
                                            @endif
                                        </td>
                                        <td>{{ $medicine->batch_number }}</td>
                                        <td>
                                            <span class="{{ $medicine->quantity < 10 ? 'text-warning' : '' }}">
                                                {{ $medicine->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="{{ $medicine->expiry_date < now() ? 'text-danger' : '' }}">
                                                {{ $medicine->expiry_date->format('Y-m-d') }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($medicine->price, 2) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#updateStockModal{{ $medicine->id }}">
                                                    Update Stock
                                                </button>
                                                @if($medicine->expiry_date < now())
                                                    <form action="{{ route('pharmacy.medication-management.mark-expired', $medicine->id) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Are you sure you want to mark this medicine as expired?')">
                                                            Mark Expired
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Update Stock Modal -->
                                    <div class="modal fade" id="updateStockModal{{ $medicine->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Stock for {{ $medicine->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pharmacy.medication-management.update-stock', $medicine->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Current Quantity</label>
                                                            <input type="number" class="form-control" value="{{ $medicine->quantity }}" disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">New Quantity</label>
                                                            <input type="number" name="quantity" class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Batch Number</label>
                                                            <input type="text" name="batch_number" class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Expiry Date</label>
                                                            <input type="date" name="expiry_date" class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Price per Unit</label>
                                                            <input type="number" step="0.01" name="price" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update Stock</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $medicines->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
