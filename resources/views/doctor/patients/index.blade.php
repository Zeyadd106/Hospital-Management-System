@extends('layouts.doctor')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Patients Management</h5>
                        <a href="{{ route('doctor.patients.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add New Patient
                        </a>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Last Visit</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div>
                                                    <img src="{{ $patient->avatar ?? asset('img/default-avatar.jpg') }}" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $patient->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $patient->gender ?? 'Not specified' }} | {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') . ' (' . $patient->date_of_birth->age . ' years)' : 'DOB not specified' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $patient->email }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $patient->phone ?? 'No phone' }}</p>
                                        </td>
                                        <td>
                                            @if($patient->appointments->count() > 0)
                                                <p class="text-xs font-weight-bold mb-0">{{ $patient->appointments->first()->appointment_date->format('M d, Y') }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $patient->appointments->first()->appointment_date->format('h:i A') }}</p>
                                            @else
                                                <p class="text-xs text-secondary mb-0">No visits yet</p>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-{{ $patient->appointments->count() > 0 && $patient->appointments->first()->status == 'completed' ? 'success' : 'secondary' }}">
                                                {{ $patient->appointments->count() > 0 ? ucfirst($patient->appointments->first()->status) : 'No appointments' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-link text-info me-2" title="View Patient">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('doctor.patients.edit', $patient->id) }}" class="btn btn-link text-warning me-2" title="Edit Patient">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('doctor.appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-link text-primary me-2" title="Schedule Appointment">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </a>
                                                <a href="{{ route('doctor.prescriptions.create', ['patient_id' => $patient->id]) }}" class="btn btn-link text-success" title="Create Prescription">
                                                    <i class="fas fa-prescription"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-sm mb-0">No patients found</p>
                                        </td>
                                    </tr>
                                @endforelse
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
