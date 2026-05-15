@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Medication</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('pharmacy.medication-management.update', $medication->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Medication Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $medication->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $medication->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="dosage">Dosage</label>
                                    <input type="text" class="form-control @error('dosage') is-invalid @enderror" id="dosage" name="dosage" value="{{ old('dosage', $medication->dosage) }}" required>
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
                                        <option value="tablet" {{ old('form', $medication->form) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="capsule" {{ old('form', $medication->form) == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                        <option value="syrup" {{ old('form', $medication->form) == 'syrup' ? 'selected' : '' }}>Syrup</option>
                                        <option value="injection" {{ old('form', $medication->form) == 'injection' ? 'selected' : '' }}>Injection</option>
                                        <option value="cream" {{ old('form', $medication->form) == 'cream' ? 'selected' : '' }}>Cream</option>
                                    </select>
                                    @error('form')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="manufacturer">Manufacturer</label>
                                    <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $medication->manufacturer) }}" required>
                                    @error('manufacturer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Current Stock</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $medication->stock->quantity) }}" required min="0">
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="reorder_point">Reorder Point</label>
                                    <input type="number" class="form-control @error('reorder_point') is-invalid @enderror" id="reorder_point" name="reorder_point" value="{{ old('reorder_point', $medication->stock->reorder_point) }}" required min="0">
                                    @error('reorder_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Medication</button>
                            <a href="{{ route('pharmacy.medication-management') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
