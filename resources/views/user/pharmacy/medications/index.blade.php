@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Available Medications</h4>
                </div>

                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form action="{{ route('user.pharmacy.medications.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="requires_prescription">Prescription</label>
                                    <select name="requires_prescription" id="requires_prescription" class="form-control">
                                        <option value="">All Medications</option>
                                        <option value="1" {{ request('requires_prescription') == '1' ? 'selected' : '' }}>Requires Prescription</option>
                                        <option value="0" {{ request('requires_prescription') == '0' ? 'selected' : '' }}>No Prescription Needed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_price">Min Price</label>
                                    <input type="number" name="min_price" id="min_price" class="form-control" 
                                           value="{{ request('min_price') }}" min="0">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="max_price">Max Price</label>
                                    <input type="number" name="max_price" id="max_price" class="form-control" 
                                           value="{{ request('max_price') }}" min="0">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock" 
                                               {{ request('in_stock') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="in_stock">
                                            In Stock Only
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Medications List -->
                    <div class="row">
                        @foreach($medications as $medication)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $medication->name }}</h5>
                                        <p class="card-text">{{ Str::limit($medication->description, 100) }}</p>
                                        <p class="card-text">
                                            <strong>Dosage:</strong> {{ $medication->dosage }}<br>
                                            <strong>Price:</strong> ${{ number_format($medication->price, 2) }}<br>
                                            <strong>Stock:</strong> {{ $medication->stock_quantity ?? 0 }} units
                                        </p>
                                        <a href="{{ route('user.pharmacy.medications.show', $medication->id) }}" 
                                           class="btn btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $medications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
