@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Add New Medication</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pharmacy.medication-management.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Medication Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="dosage">Dosage</label>
                                    <input type="text" class="form-control @error('dosage') is-invalid @enderror" id="dosage" name="dosage" value="{{ old('dosage') }}" required>
                                    @error('dosage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="form">Form</label>
                                    <select class="form-control @error('form') is-invalid @enderror" id="form" name="form" required>
                                        <option value="">Select form</option>
                                        <option value="tablet">Tablet</option>
                                        <option value="capsule">Capsule</option>
                                        <option value="syrup">Syrup</option>
                                        <option value="injection">Injection</option>
                                        <option value="cream">Cream</option>
                                    </select>
                                    @error('form')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="manufacturer">Manufacturer</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}" required>
                                    @error('manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="initial_stock">Initial Stock</label>
                                    <input type="number" class="form-control @error('initial_stock') is-invalid @enderror" id="initial_stock" name="initial_stock" value="{{ old('initial_stock') }}" required min="0">
                                    @error('initial_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="reorder_point">Reorder Point</label>
                                    <input type="number" class="form-control @error('reorder_point') is-invalid @enderror" id="reorder_point" name="reorder_point" value="{{ old('reorder_point') }}" required min="0">
                                    @error('reorder_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Add Medication</button>
                            <a href="{{ route('pharmacy.medication-management') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
