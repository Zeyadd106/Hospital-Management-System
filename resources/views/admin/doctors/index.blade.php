@extends('admin.layouts.app')

@section('title', 'Doctors List')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Doctors List</h4>
        <a href="{{ route('admin.doctors.create') }}" class="btn btn-custom">
            <i class="fas fa-plus me-1"></i> Add New Doctor
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
                            <th>Department</th>
                            <th>Specialization</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $doctor)
                            <tr>
                                <td>{{ $doctor->id }}</td>
                                <td>{{ $doctor->name }}</td>
                                <td>{{ $doctor->email }}</td>
                                <td>{{ $doctor->department }}</td>
                                <td>{{ $doctor->specialization }}</td>
                                <td>
                                    <span class="badge {{ $doctor->status === 'active' ? 'badge-confirmed' : 'badge-cancelled' }}">
                                        {{ ucfirst($doctor->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-custom btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this doctor?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No doctors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $doctors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection