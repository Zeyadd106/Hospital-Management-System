<x-layout>
    <x-slot name="title">My Medication Management - MediCare</x-slot>
    
    <div class="main-content" style="align-items: flex-start;">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Medication Management</h2>
                <a href="{{ route('pharmacy.medication-management') }}#enrollment-form" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>New Enrollment
                </a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Active Enrollments</h5>
                </div>
                <div class="card-body">
                    @if($managements->where('status', '!=', 'cancelled')->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Enrollment Date</th>
                                        <th>Services</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($managements->where('status', '!=', 'cancelled') as $management)
                                        <tr>
                                            <td>{{ $management->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($management->services)
                                                    @foreach($management->services as $service)
                                                        <span class="badge bg-primary mb-1">{{ str_replace('_', ' ', ucfirst($service)) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">None specified</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-{{ $management->status_badge }}">{{ ucfirst($management->status) }}</span></td>
                                            <td>
                                                <a href="{{ route('pharmacy.medication-management.show', $management->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0">You don't have any active medication management enrollments.</p>
                            <a href="{{ route('pharmacy.medication-management') }}#enrollment-form" class="btn btn-primary mt-3">Enroll Now</a>
                        </div>
                    @endif
                </div>
            </div>
            
            @if($managements->where('status', '=', 'cancelled')->count() > 0)
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Past Enrollments</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Enrollment Date</th>
                                        <th>Services</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($managements->where('status', '=', 'cancelled') as $management)
                                        <tr class="text-muted">
                                            <td>{{ $management->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($management->services)
                                                    @foreach($management->services as $service)
                                                        <span class="badge bg-secondary mb-1">{{ str_replace('_', ' ', ucfirst($service)) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">None specified</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-danger">Cancelled</span></td>
                                            <td>
                                                <a href="{{ route('pharmacy.medication-management.show', $management->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Verify Enrollment</h5>
                </div>
                <div class="card-body">
                    <p>Have a confirmation code? Verify your enrollment details below:</p>
                    <form action="{{ route('pharmacy.medication-management.verify') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="confirmation_code" placeholder="Enter confirmation code" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">Verify</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>

