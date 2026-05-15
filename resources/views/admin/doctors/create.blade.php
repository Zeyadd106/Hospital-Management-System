@extends('admin.layouts.app')

@section('title', 'Create Doctor')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Create New Doctor</h4>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Doctors
        </a>
    </div>
    
    <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="department" class="form-label">Department</label>
                <select class="form-select @error('department') is-invalid @enderror" id="department" name="department" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->name }}" {{ old('department') === $department->name ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="specialization" class="form-label">Specialization</label>
                <input type="text" class="form-control @error('specialization') is-invalid @enderror" id="specialization" name="specialization" value="{{ old('specialization') }}" required>
                @error('specialization')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="working_hours" class="form-label">Working Hours</label>
                <input type="text" class="form-control @error('working_hours') is-invalid @enderror" id="working_hours" name="working_hours" value="{{ old('working_hours') }}">
                @error('working_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio') }}</textarea>
            @error('bio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="image" class="form-label">Profile Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
            <button type="submit" class="btn btn-custom">Create Doctor</button>
        </div>
    </form>
</div>
@endsection