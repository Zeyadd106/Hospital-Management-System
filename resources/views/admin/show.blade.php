@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>User Details: {{ $user->name }}</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $user->id }}</p>
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Created At:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                    <p><strong>Updated At:</strong> {{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection