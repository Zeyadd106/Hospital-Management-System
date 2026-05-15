@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Vaccination Reports</h2>
        <a href="{{ route('doctor.vaccinations.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-syringe me-1"></i> All Vaccinations
        </a>
    </div>

    @if($reports->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-file-medical-alt fa-3x mb-3"></i>
            <p class="lead">No vaccination reports available.</p>
        </div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Vaccine</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $report->user->avatar ?? asset('images/default-avatar.png') }}" 
                                                 class="rounded-circle me-2" 
                                                 style="width: 35px; height: 35px; object-fit: cover;">
                                            {{ $report->user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $report->vaccine->name }}</td>
                                    <td>{{ $report->appointment_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $report->status === 'completed' ? 'success' : 
                                            ($report->status === 'pending' ? 'warning' : 'secondary') 
                                        }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('doctor.vaccinations.show', $report->id) }}" 
                                               class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($report->status === 'pending')
                                                <form action="{{ route('doctor.vaccinations.confirm', $report->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($reports->hasPages())
                <div class="card-footer">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
    }
</style>
@endsection
