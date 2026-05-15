@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Include the sidebar -->
        @include('pharmacy.partials.sidebar')
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h3 mb-0">Add New Medication</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('pharmacy.medications.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Medication Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pharmacy.medications.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Medication Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="manufacturer" class="form-label">Manufacturer *</label>
                                <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" 
                                       id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}" required>
                                @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="strength" class="form-label">Strength *</label>
                                <input type="text" class="form-control @error('strength') is-invalid @enderror" 
                                       id="strength" name="strength" value="{{ old('strength') }}" required>
                                @error('strength')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="form" class="form-label">Form *</label>
                                <select class="form-select @error('form') is-invalid @enderror" id="form" name="form" required>
                                    <option value="" disabled selected>Select form</option>
                                    <option value="Tablet" {{ old('form') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="Capsule" {{ old('form') == 'Capsule' ? 'selected' : '' }}>Capsule</option>
                                    <option value="Syrup" {{ old('form') == 'Syrup' ? 'selected' : '' }}>Syrup</option>
                                    <option value="Injection" {{ old('form') == 'Injection' ? 'selected' : '' }}>Injection</option>
                                    <option value="Ointment" {{ old('form') == 'Ointment' ? 'selected' : '' }}>Ointment</option>
                                    <option value="Drops" {{ old('form') == 'Drops' ? 'selected' : '' }}>Drops</option>
                                    <option value="Inhaler" {{ old('form') == 'Inhaler' ? 'selected' : '' }}>Inhaler</option>
                                    <option value="Other" {{ old('form') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('form')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="batch_number" class="form-label">Batch Number *</label>
                                <input type="text" class="form-control @error('batch_number') is-invalid @enderror" 
                                       id="batch_number" name="batch_number" value="{{ old('batch_number') }}" required>
                                @error('batch_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">Quantity *</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="price" class="form-label">Price per Unit *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" min="0" required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="expiry_date" class="form-label">Expiry Date *</label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" name="expiry_date" 
                                       value="{{ old('expiry_date') }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="current_stock" class="form-label">Current Stock *</label>
                                <input type="number" class="form-control @error('current_stock') is-invalid @enderror" 
                                       id="current_stock" name="current_stock" value="{{ old('current_stock', 0) }}" min="0" required>
                                @error('current_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="min_stock" class="form-label">Minimum Stock Level *</label>
                                <input type="number" class="form-control @error('min_stock') is-invalid @enderror" 
                                       id="min_stock" name="min_stock" value="{{ old('min_stock', 10) }}" min="1" required>
                                <small class="form-text text-muted">Alert when stock falls below this level</small>
                                @error('min_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-secondary me-md-2">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Medication
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    // Set minimum date for expiry date to tomorrow
    document.addEventListener('DOMContentLoaded', function() {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minDate = tomorrow.toISOString().split('T')[0];
        
        const expiryDateField = document.getElementById('expiry_date');
        if (expiryDateField && !expiryDateField.value) {
            expiryDateField.min = minDate;
        }
    });
</script>
@endpush
@endsection
