@extends('layouts.pharmacy')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Stock History: {{ $medication->name }}</h1>
        <div>
            <a href="{{ route('pharmacy.medications.show', $medication) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Medication
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Added
                            </span>
                            <h6 class="h4 mb-0 text-gray-800">{{ $summary['total_added'] }}</h6>
                        </div>
                        <div class="icon-circle bg-primary text-white">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-danger h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Removed
                            </span>
                            <h6 class="h4 mb-0 text-gray-800">{{ $summary['total_removed'] }}</h6>
                        </div>
                        <div class="icon-circle bg-danger text-white">
                            <i class="fas fa-minus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Net Change
                            </span>
                            <h6 class="h4 mb-0 text-gray-800">
                                {{ $summary['net_change'] >= 0 ? '+' : '' }}{{ $summary['net_change'] }}
                                @if($summary['net_change'] > 0)
                                    <span class="text-success"><i class="fas fa-arrow-up"></i></span>
                                @elseif($summary['net_change'] < 0)
                                    <span class="text-danger"><i class="fas fa-arrow-down"></i></span>
                                @endif
                            </h6>
                        </div>
                        <div class="icon-circle bg-info text-white">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Adjustments
                            </span>
                            <h6 class="h4 mb-0 text-gray-800">{{ $summary['total_adjustments'] }}</h6>
                        </div>
                        <div class="icon-circle bg-warning text-white">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Stock Adjustment History</h6>
            <div class="text-muted small">
                Current Stock: <span class="fw-bold">{{ $stockItem->current_stock }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($adjustments->isEmpty())
                <div class="alert alert-info mb-0">No stock adjustments found for this medication.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Type</th>
                                <th class="text-end">Adjustment</th>
                                <th class="text-end">Previous Qty</th>
                                <th class="text-end">New Qty</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adjustments as $adjustment)
                                <tr>
                                    <td>{{ $adjustment->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $adjustment->user->name }}</td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'add' => 'success',
                                                'remove' => 'danger',
                                                'set' => 'primary'
                                            ][$adjustment->adjustment_type] ?? 'secondary';
                                            
                                            $typeLabels = [
                                                'add' => 'Added',
                                                'remove' => 'Removed',
                                                'set' => 'Adjusted'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $typeLabels[$adjustment->adjustment_type] ?? ucfirst($adjustment->adjustment_type) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold {{ $adjustment->adjustment_type === 'add' ? 'text-success' : ($adjustment->adjustment_type === 'remove' ? 'text-danger' : '') }}">
                                        {{ $adjustment->adjustment_type === 'add' ? '+' : ($adjustment->adjustment_type === 'remove' ? '-' : '') }}
                                        {{ $adjustment->quantity }}
                                    </td>
                                    <td class="text-end">{{ $adjustment->previous_quantity }}</td>
                                    <td class="text-end fw-bold">{{ $adjustment->new_quantity }}</td>
                                    <td>
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $adjustment->reason }}">
                                            {{ Str::limit($adjustment->reason, 30) }}
                                            @if(strlen($adjustment->reason) > 30)
                                                <i class="fas fa-info-circle text-muted ms-1"></i>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $adjustments->firstItem() }} to {{ $adjustments->lastItem() }} of {{ $adjustments->total() }} entries
                    </div>
                    <div>
                        {{ $adjustments->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
        </div>
        <div class="card-body">
            <div class="d-grid gap-2 d-md-flex">
                <a href="#" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addStockModal">
                    <i class="fas fa-plus-circle me-1"></i> Add Stock
                </a>
                <a href="#" class="btn btn-warning text-white me-2" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                    <i class="fas fa-adjust me-1"></i> Adjust Stock
                </a>
                <a href="{{ route('pharmacy.medications.edit', $medication) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Medication
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Include the stock modals -->
@include('pharmacy.medications.partials.stock-modals', ['medication' => $medication])

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8em;
    }
    .icon-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 3rem;
        width: 3rem;
        border-radius: 100%;
    }
    .text-xs {
        font-size: 0.7rem;
    }
    .text-uppercase {
        letter-spacing: 0.1em;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

<!-- Toast Container -->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100"></div>

@endsection
