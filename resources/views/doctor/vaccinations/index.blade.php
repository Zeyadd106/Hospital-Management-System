@extends('layouts.doctor')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Vaccination Bookings</h5>
                        <div>
                            <a href="{{ route('doctor.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4 mt-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mx-4 mt-3" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Patient</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vaccine</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date & Time</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vaccinations as $vaccination)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ $vaccination->user->avatar ?? asset('img/default-avatar.jpg') }}" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $vaccination->user->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $vaccination->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $vaccination->vaccine->name }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $vaccination->vaccine->manufacturer }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $vaccination->appointment_date->format('M d, Y') }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $vaccination->appointment_date->format('h:i A') }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-{{ $vaccination->status == 'pending' ? 'warning' : ($vaccination->status == 'completed' ? 'success' : ($vaccination->status == 'approved' ? 'info' : 'danger')) }}">
                                                {{ ucfirst($vaccination->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if($vaccination->status == 'pending')
                                                    <form action="{{ route('doctor.vaccinations.approve', $vaccination->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('doctor.vaccinations.reject', $vaccination->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($vaccination->status == 'approved')
                                                    <form action="{{ route('doctor.vaccinations.complete', $vaccination->id) }}" method="POST" class="me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Mark as Completed">
                                                            <i class="fas fa-check-double"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('doctor.vaccinations.show', $vaccination->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-sm mb-0">No vaccination bookings found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $vaccinations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
