@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>User Details</h4>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-custom me-2">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Users
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width: 30%">ID</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'badge-cancelled' : ($user->role === 'doctor' ? 'badge-confirmed' : ($user->role === 'staff' ? 'badge-pending' : 'badge-confirmed')) }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $user->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $user->address ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $user->gender ? ucfirst($user->gender) : 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection