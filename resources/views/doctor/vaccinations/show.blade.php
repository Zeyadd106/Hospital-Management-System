@extends('layouts.doctor')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Vaccination Details</h5>
                        <a href="{{ route('doctor.vaccinations.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Vaccinations
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Patient Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-xl me-3">
                                            <img src="{{ $vaccination->user->avatar ?? asset('img/default-avatar.jpg') }}" alt="User Image" class="rounded-circle">
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $vaccination->user->name }}</h6>
                                            <p class="text-sm text-secondary mb-0">{{ $vaccination->user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Phone:</strong> {{ $vaccination->user->phone ?? 'Not provided' }}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Date of Birth:</strong> {{ $vaccination->user->date_of_birth ? $vaccination->user->date_of_birth->format('M d, Y') : 'Not provided' }}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Gender:</strong> {{ ucfirst($vaccination->user->gender ?? 'Not provided') }}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Address:</strong> {{ $vaccination->user->address ?? 'Not provided' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Vaccination Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong class="text-dark">Vaccine:</strong> {{ $vaccination->vaccine->name }}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Manufacturer:</strong> {{ $vaccination->vaccine->manufacturer }}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Appointment Date:</strong> {{ $vaccination->appointment_date->format('M d, Y') }}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Appointment Time:</strong> {{ $vaccination->appointment_date->format('h:i A') }}
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-dark">Status:</strong>
                                        <span class="badge badge-sm bg-{{ $vaccination->status == 'pending' ? 'warning' : ($vaccination->status == 'completed' ? 'success' : ($vaccination->status == 'approved' ? 'info' : 'danger')) }}">
                                            {{ ucfirst($vaccination->status) }}
                                        </span>
                                    </div>
                                    @if($vaccination->notes)
                                        <div class="mb-2">
                                            <strong class="text-dark">Notes:</strong> {{ $vaccination->notes }}
                                        </div>
                                    @endif
                                    @if($vaccination->status == 'rejected' && $vaccination->rejection_reason)
                                        <div class="mb-2">
                                            <strong class="text-dark">Rejection Reason:</strong> {{ $vaccination->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        @if($vaccination->status == 'pending')
                                            <form action="{{ route('doctor.vaccinations.approve', $vaccination->id) }}" method="POST" class="me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-check me-1"></i> Approve Vaccination
                                                </button>
                                            </form>
                                            <form action="{{ route('doctor.vaccinations.reject', $vaccination->id) }}" method="POST">
                                                @csrf
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                    <i class="fas fa-times me-1"></i> Reject Vaccination
                                                </button>
                                            </form>
                                        @elseif($vaccination->status == 'approved')
                                            <form action="{{ route('doctor.vaccinations.complete', $vaccination->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-check-double me-1"></i> Mark as Completed
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Vaccination</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('doctor.vaccinations.reject', $vaccination->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Vaccination</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
