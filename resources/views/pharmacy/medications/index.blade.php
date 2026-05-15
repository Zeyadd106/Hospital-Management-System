@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('pharmacy.partials.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h3 mb-0">Medication Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('pharmacy.medications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Medication
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">Medication List</h6>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search medications..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="medicationsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Manufacturer</th>
                                    <th>Strength</th>
                                    <th>Form</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medications as $medication)
                                    @php
                                        $stock = $medication->stock;
                                        $stockLevel = $stock ? $stock->current_stock : 0;
                                        $minStock = $stock ? $stock->min_stock : 0;
                                        $status = $stockLevel <= 0 ? 'Out of Stock' : ($stockLevel <= $minStock ? 'Low Stock' : 'In Stock');
                                        $statusClass = $stockLevel <= 0 ? 'danger' : ($stockLevel <= $minStock ? 'warning' : 'success');
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('pharmacy.medications.show', $medication) }}">
                                                {{ $medication->name }}
                                            </a>
                                        </td>
                                        <td>{{ $medication->manufacturer }}</td>
                                        <td>{{ $medication->strength }}</td>
                                        <td>{{ $medication->form }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                    @php
                                                        $maxStock = $minStock * 3;
                                                        $percentage = min(100, max(0, ($stockLevel / $maxStock) * 100));
                                                    @endphp
                                                    <div class="progress-bar bg-{{ $statusClass }}" role="progressbar" 
                                                         style="width: {{ $percentage }}%" 
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <span class="small">{{ $stockLevel }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('pharmacy.medications.show', $medication) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('pharmacy.medications.edit', $medication) }}" 
                                                   class="btn btn-sm btn-outline-secondary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($stock)
                                                <a href="{{ route('pharmacy.stock.history', $stock->id) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Stock History">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-pills fa-3x mb-3"></i>
                                                <p class="mb-0">No medications found.</p>
                                                <a href="{{ route('pharmacy.medications.create') }}" class="btn btn-primary mt-2">
                                                    <i class="fas fa-plus me-1"></i> Add Your First Medication
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($medications->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $medications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const table = document.getElementById('medicationsTable');
        const rows = table.querySelectorAll('tbody tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }

        searchButton.addEventListener('click', filterTable);
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') filterTable();
        });

        // Auto-hide alerts after 5 seconds
        var alertList = document.querySelectorAll('.alert');
        alertList.forEach(function(alert) {
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endpush

@endsection
