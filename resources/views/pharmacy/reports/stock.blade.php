@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        @include('pharmacy.partials.sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h3 mb-0">Stock Report</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="exportPdf">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="exportExcel">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item active" href="#" data-status="all">All Items</a></li>
                        <li><a class="dropdown-item" href="#" data-status="low">Low Stock Only</a></li>
                        <li><a class="dropdown-item" href="#" data-status="sufficient">Sufficient Stock</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" data-sort="quantity-asc">Sort by Quantity (Low to High)</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="quantity-desc">Sort by Quantity (High to Low)</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="name-asc">Sort by Name (A-Z)</a></li>
                        <li><a class="dropdown-item" href="#" data-sort="name-desc">Sort by Name (Z-A)</a></li>
                    </ul>
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
                    <h6 class="m-0 font-weight-bold">Current Stock Levels</h6>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search medications..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="stockTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Current Stock</th>
                                    <th>Min. Stock Level</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockReport as $item)
                                    @php
                                        $statusClass = $item['stock_status'] === 'Low Stock' ? 'warning' : 'success';
                                        $percentage = $item['min_stock'] > 0 
                                            ? min(100, ($item['quantity'] / ($item['min_stock'] * 2)) * 100) 
                                            : 0;
                                    @endphp
                                    <tr data-status="{{ strtolower(str_replace(' ', '-', $item['stock_status'])) }}">
                                        <td>
                                            <strong>{{ $item['medicine_name'] }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                    <div class="progress-bar bg-{{ $statusClass }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%" 
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <span class="small">{{ $item['quantity'] }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item['min_stock'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $item['stock_status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $item['last_updated'] ? $item['last_updated']->diffForHumans() : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="#" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Add Stock">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p class="mb-0">No stock data available.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing <span id="showingCount">{{ count($stockReport) }}</span> of {{ count($stockReport) }} items
                        </div>
                        <div>
                            <span class="badge bg-success me-2">Sufficient: {{ collect($stockReport)->where('stock_status', 'Sufficient Stock')->count() }}</span>
                            <span class="badge bg-warning">Low: {{ collect($stockReport)->where('stock_status', 'Low Stock')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    .progress {
        min-width: 80px;
        background-color: #e9ecef;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

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
        const table = document.getElementById('stockTable');
        const rows = table.querySelectorAll('tbody tr');
        const showingCount = document.getElementById('showingCount');
        let currentFilter = 'all';

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchesSearch = text.includes(searchTerm);
                const matchesFilter = currentFilter === 'all' || 
                                    (currentFilter === 'low' && row.getAttribute('data-status') === 'low-stock') ||
                                    (currentFilter === 'sufficient' && row.getAttribute('data-status') === 'sufficient-stock');
                
                if (matchesSearch && matchesFilter) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            showingCount.textContent = visibleCount;
        }

        // Event listeners
        searchButton.addEventListener('click', filterTable);
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') filterTable();
        });

        // Filter dropdown
        document.querySelectorAll('.dropdown-item[data-status]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                currentFilter = this.getAttribute('data-status');
                document.querySelector('.dropdown-item.active').classList.remove('active');
                this.classList.add('active');
                filterTable();
            });
        });

        // Sort functionality
        document.querySelectorAll('.dropdown-item[data-sort]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const [sortBy, order] = this.getAttribute('data-sort').split('-');
                sortTable(sortBy, order);
            });
        });

        function sortTable(sortBy, order) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                let aValue, bValue;
                
                if (sortBy === 'name') {
                    aValue = a.cells[0].textContent.trim().toLowerCase();
                    bValue = b.cells[0].textContent.trim().toLowerCase();
                    return order === 'asc' 
                        ? aValue.localeCompare(bValue) 
                        : bValue.localeCompare(aValue);
                } else if (sortBy === 'quantity') {
                    aValue = parseInt(a.cells[1].querySelector('.small').textContent.trim());
                    bValue = parseInt(b.cells[1].querySelector('.small').textContent.trim());
                    return order === 'asc' ? aValue - bValue : bValue - aValue;
                }
                return 0;
            });
            
            // Remove existing rows
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }
            
            // Add sorted rows
            rows.forEach(row => tbody.appendChild(row));
        }

        // Export buttons
        document.getElementById('exportPdf').addEventListener('click', function() {
            // Implement PDF export logic here
            alert('PDF export functionality will be implemented here');
        });

        document.getElementById('exportExcel').addEventListener('click', function() {
            // Implement Excel export logic here
            alert('Excel export functionality will be implemented here');
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
