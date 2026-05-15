@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">My Patients</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Appointments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                <tr>
                                    <td>{{ $patient->name }}</td>
                                    <td>{{ $patient->age }}</td>
                                    <td>{{ ucfirst($patient->gender) }}</td>
                                    <td>{{ $patient->phone }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $patient->appointments->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('doctor.patients.edit', $patient->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('doctor.patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
