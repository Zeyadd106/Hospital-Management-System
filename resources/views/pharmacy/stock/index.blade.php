@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Pharmacy Stock Management') }}
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStockModal">
                        Add New Stock
                    </button>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Medicine Name</th>
                                <th>Quantity</th>
                                <th>Expiry Date</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->medicine_name }}</td>
                                    <td>{{ $stock->quantity }}</td>
                                    <td>{{ \Carbon\Carbon::parse($stock->expiry_date)->format('d M Y') }}</td>
                                    <td>{{ number_format($stock->price, 2) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pharmacy.stock.edit', $stock->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('pharmacy.stock.destroy', $stock->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No stock items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $stocks->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Stock Modal -->
    <div class="modal fade" id="addStockModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Stock Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pharmacy.stock.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="medicine_name" class="form-label">Medicine Name</label>
                            <input type="text" class="form-control" id="medicine_name" name="medicine_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required min="0">
                        </div>
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required min="0" step="0.01">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
