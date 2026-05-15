@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Reports</h4>
        <div class="btn-group">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Health Screening Reports</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Test</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Results</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->patient->name }}</td>
                                <td>{{ $report->test->name }}</td>
                                <td>{{ $report->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $report->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($report->status === 'completed')
                                        <button class="btn btn-sm btn-info view-results-btn" data-id="{{ $report->id }}">
                                            <i class="fas fa-eye me-1"></i> View Results
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    @if($report->status === 'pending')
                                        <button class="btn btn-sm btn-success approve-btn" data-id="{{ $report->id }}">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-danger reject-btn" data-id="{{ $report->id }}">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Results Modal -->
<div class="modal fade" id="viewResultsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Health Screening Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Results will be loaded here via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle report actions
        $('.approve-btn').click(function() {
            const reportId = $(this).data('id');
            // Add AJAX call to approve report
        });

        $('.reject-btn').click(function() {
            const reportId = $(this).data('id');
            // Add AJAX call to reject report
        });

        $('.view-results-btn').click(function() {
            const reportId = $(this).data('id');
            // Add AJAX call to fetch and display results
        });
    });
</script>
@endsection
