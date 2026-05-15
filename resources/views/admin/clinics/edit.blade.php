@extends('admin.layouts.app')

@section('title', 'Edit Clinic')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-clinic-medical me-2"></i>Edit Clinic: {{ $clinic->name }}</h4>
        <a href="{{ route('admin.clinics.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Clinics
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.clinics.update', $clinic->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $clinic->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="department_id" class="form-label">Department</label>
                    <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $clinic->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $clinic->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image (Leave blank to keep current)</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                    @if($clinic->image)
                        <div class="mt-2">
                            <small>Current Image: <a href="{{ Storage::url($clinic->image) }}" target="_blank">View</a></small>
                        </div>
                    @endif
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $clinic->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $clinic->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.clinics.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-custom">Update Clinic</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection