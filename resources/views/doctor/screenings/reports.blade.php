@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Health Screening Reports</h2>
        <div>
            <a href="{{ route('doctor.screenings.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Screenings
            </a>
            <button id="printReport" class="btn btn-primary">
                <i class="fas fa-print me-1"></i> Print Report
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Screenings</h5>
                    <p class="display-4">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <p class="display-4">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <p class="display-4">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Rejected</h5>
                    <p class="display-4">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Screening Reports</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.screenings.reports') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="test_id" class="form-select">
                            <option value="">All Tests</option>
                            @foreach($tests as $test)
                                <option value="{{ $test->id }}" {{ request('test_id') == $test->id ? 'selected' : '' }}>
                                    {{ $test->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}" placeholder="Start Date">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date') }}" placeholder="End Date">
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i> Generate
                        </button>
                        <a href="{{ route('doctor.screenings.reports') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div id="printableArea">
                <div class="d-flex justify-content-between align-items-center mb-4 print-only">
                    <h3>Health Screening Report</h3>
                    <p>Generated: {{ now()->format('F d, Y') }}</p>
                </div>

                <!-- Test Type Distribution Chart -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Test Type Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="testTypeChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Status Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="statusChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Monthly Screening Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyTrendChart" width="800" height="300"></canvas>
                    </div>
                </div>

                <!-- Detailed Report Table -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Detailed Report</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Test</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Results</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($screenings as $screening)
                                        <tr>
                                            <td>{{ $screening->user->name }}</td>
                                            <td>{{ $screening->test->name }}</td>
                                            <td>{{ $screening->date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $screening->status === 'completed' ? 'success' : 
                                                    ($screening->status === 'pending' ? 'warning' : 
                                                    ($screening->status === 'approved' ? 'info' : 'danger')) 
                                                }}">
                                                    {{ ucfirst($screening->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($screening->status === 'completed' && $screening->results)
                                                    <button type="button" class="btn btn-sm btn-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#resultModal{{ $screening->id }}">
                                                        View Results
                                                    </button>
                                                @else
                                                    <span class="text-muted">Not available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted">No screening data available for the selected criteria.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Modals -->
    @foreach($screenings as $screening)
        @if($screening->status === 'completed' && $screening->results)
            <div class="modal fade" id="resultModal{{ $screening->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Screening Results: {{ $screening->test->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>Patient:</strong> {{ $screening->user->name }}</p>
                                    <p><strong>Test Date:</strong> {{ $screening->date->format('M d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Doctor:</strong> Dr. {{ auth()->user()->name }}</p>
                                    <p><strong>Report Date:</strong> {{ $screening->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    {!! $screening->results !!}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="printResults('resultModal{{ $screening->id }}')">
                                <i class="fas fa-print me-1"></i> Print Results
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection

@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
    .print-only {
        display: none;
    }
    @media print {
        .print-only {
            display: block;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Print functionality
    document.getElementById('printReport').addEventListener('click', function() {
        window.print();
    });

    function printResults(modalId) {
        const modalContent = document.getElementById(modalId).querySelector('.modal-body');
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = modalContent.innerHTML;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

    // Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Test Type Distribution Chart
        const testTypeCtx = document.getElementById('testTypeChart').getContext('2d');
        const testTypeChart = new Chart(testTypeCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($chartData['testTypes']['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chartData['testTypes']['data']) !!},
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#5a5c69',
                        '#2e59d9', '#17a673', '#2c9faf', '#f5b74f', '#e04c4c', '#858796'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Test Type Distribution'
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $stats['completed'] }},
                        {{ $stats['pending'] }},
                        {{ $stats['approved'] ?? 0 }},
                        {{ $stats['rejected'] }}
                    ],
                    backgroundColor: ['#1cc88a', '#f6c23e', '#4e73df', '#e74a3b'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Status Distribution'
                    }
                }
            }
        });

        // Monthly Trend Chart
        const trendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        const trendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['monthlyTrend']['labels']) !!},
                datasets: [{
                    label: 'Screenings',
                    data: {!! json_encode($chartData['monthlyTrend']['data']) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Monthly Screening Trend'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
