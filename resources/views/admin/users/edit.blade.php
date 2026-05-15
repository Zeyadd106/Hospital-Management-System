@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Edit User: {{ $user->name }}</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>
    
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label">Password (leave blank to keep current)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="patient" {{ (old('role', $user->role) === 'patient') ? 'selected' : '' }}>Patient</option>
                    <option value="doctor" {{ (old('role', $user->role) === 'doctor') ? 'selected' : '' }}>Doctor</option>
                    <option value="staff" {{ (old('role', $user->role) === 'staff') ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ (old('role', $user->role) === 'admin') ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                @error('date_of_birth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="male" {{ (old('gender', $user->gender) === 'male') ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ (old('gender', $user->gender) === 'female') ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ (old('gender', $user->gender) === 'other') ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update User</button>
        </div>
    </form>
</div>
@endsection