@extends('layouts.doctor')

@section('content')
<div class="container">
    <h1 class="mb-4">Prescription Requests</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            Prescription Requests
        </div>
        <div class="card-body">
            @if($prescriptions->isEmpty())
                <p class="text-center">No prescription requests found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Medications</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prescriptions as $prescription)
                                <tr>
                                    <td>{{ $prescription->patient->name }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($prescription->medications as $med)
                                                <li>{{ $med->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $prescription->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $prescription->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($prescription->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($prescription->status === 'pending')
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('doctor.prescriptions.approve', $prescription->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('doctor.prescriptions.reject', $prescription->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $prescriptions->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
