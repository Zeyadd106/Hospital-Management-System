@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Appointments</h5>
                                    <p class="card-text">Manage your medical appointments</p>
                                    <a href="{{ route('appointments.index') }}" class="btn btn-light">View Appointments</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Health Screenings</h5>
                                    <p class="card-text">Schedule health screenings</p>
                                    <a href="{{ route('health-screenings.index') }}" class="btn btn-light">View Screenings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Vaccinations</h5>
                                    <p class="card-text">Manage your vaccination schedule</p>
                                    <a href="{{ route('vaccination.index') }}" class="btn btn-light">View Vaccinations</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Recent Activity</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        @forelse($recentActivity as $activity)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                                    <small class="text-muted">{{ $activity['created_at']->diffForHumans() }}</small>
                                                </div>
                                                <span class="badge bg-{{ $activity['status'] }}">{{ $activity['status'] }}</span>
                                            </div>
                                        @empty
                                            <div class="list-group-item text-muted">
                                                No recent activity
                                            </div>
                                        @endforelse
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
@endsection
