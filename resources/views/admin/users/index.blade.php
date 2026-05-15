@extends('admin.layouts.app')

@section('title', 'Users List')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Users List</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-custom">
            <i class="fas fa-plus me-1"></i> Add New User
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'badge-cancelled' : ($user->role === 'doctor' ? 'badge-confirmed' : ($user->role === 'staff' ? 'badge-pending' : 'badge-confirmed')) }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-custom btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-custom btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection