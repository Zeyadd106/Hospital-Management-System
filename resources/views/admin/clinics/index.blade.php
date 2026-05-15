@extends('admin.layouts.app')

@section('title', 'Clinics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Clinics</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.clinics.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Clinic
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clinics as $clinic)
                            <tr>
                                <td>{{ $clinic->id }}</td>
                                <td>{{ $clinic->name }}</td>
                                <td>{{ $clinic->location }}</td>
                                <td>{{ $clinic->phone }}</td>
                                <td>{{ $clinic->status }}</td>
                                <td>
                                    <a href="{{ route('admin.clinics.show', $clinic->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.clinics.edit', $clinic->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.clinics.destroy', $clinic->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection