@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Medication</h1>
        <div>
            <a href="{{ route('pharmacy.medications.show', $medication) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-1"></i> View
            </a>
            <a href="{{ route('pharmacy.medications.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Medication Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('pharmacy.medications.update', $medication) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Medication Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $medication->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="manufacturer" class="form-label">Manufacturer *</label>
                        <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" 
                               id="manufacturer" name="manufacturer" value="{{ old('manufacturer', $medication->manufacturer) }}" required>
                        @error('manufacturer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="strength" class="form-label">Strength *</label>
                        <input type="text" class="form-control @error('strength') is-invalid @enderror" 
                               id="strength" name="strength" value="{{ old('strength', $medication->strength) }}" required>
                        @error('strength')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="form" class="form-label">Form *</label>
                        <select class="form-select @error('form') is-invalid @enderror" id="form" name="form" required>
                            <option value="" disabled>Select form</option>
                            @php
                                $forms = ['Tablet', 'Capsule', 'Syrup', 'Injection', 'Ointment', 'Drops', 'Inhaler', 'Other'];
                            @endphp
                            @foreach($forms as $form)
                                <option value="{{ $form }}" 
                                    {{ old('form', $medication->form) == $form ? 'selected' : '' }}>
                                    {{ $form }}
                                </option>
                            @endforeach
                        </select>
                        @error('form')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="batch_number" class="form-label">Batch Number *</label>
                        <input type="text" class="form-control @error('batch_number') is-invalid @enderror" 
                               id="batch_number" name="batch_number" value="{{ old('batch_number', $medication->batch_number) }}" required>
                        @error('batch_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantity *</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                               id="quantity" name="quantity" value="{{ old('quantity', $medication->quantity) }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price per Unit *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $medication->price) }}" min="0" required>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="expiry_date" class="form-label">Expiry Date *</label>
                        <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                               id="expiry_date" name="expiry_date" 
                               value="{{ old('expiry_date', $medication->expiry_date->format('Y-m-d')) }}" 
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
                               id="current_stock" name="current_stock" 
                               value="{{ old('current_stock', $stockItem->current_stock ?? 0) }}" 
                               min="0" required>
                        @error('current_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="min_stock" class="form-label">Minimum Stock Level *</label>
                        <input type="number" class="form-control @error('min_stock') is-invalid @enderror" 
                               id="min_stock" name="min_stock" 
                               value="{{ old('min_stock', $stockItem->min_stock ?? 10) }}" 
                               min="1" required>
                        <small class="form-text text-muted">Alert when stock falls below this level</small>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $medication->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input @error('status') is-invalid @enderror" type="checkbox" 
                               id="status" name="status" value="1" 
                               {{ old('status', $medication->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <button type="button" class="btn btn-danger me-md-2" 
                            onclick="if(confirm('Are you sure you want to delete this medication?')) { 
                                document.getElementById('delete-form').submit(); 
                            }">
                        <i class="fas fa-trash me-1"></i> Delete Medication
                    </button>
                    <div>
                        <a href="{{ route('pharmacy.medications.show', $medication) }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Medication
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form -->
            <form id="delete-form" action="{{ route('pharmacy.medications.destroy', $medication) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set minimum date for expiry date to tomorrow
    document.addEventListener('DOMContentLoaded', function() {
        const expiryDateField = document.getElementById('expiry_date');
        if (expiryDateField && !expiryDateField.value) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            expiryDateField.min = tomorrow.toISOString().split('T')[0];
        }
    });
</script>
@endpush
@endsection
