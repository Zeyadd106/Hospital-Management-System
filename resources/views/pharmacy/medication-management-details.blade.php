@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">Medication Management Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <span class="badge bg-{{ $management->status_badge }} p-2 fs-6">
                                {{ ucfirst($management->status) }}
                            </span>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Confirmation Code</h5>
                                <p class="fs-5 fw-bold">{{ $management->confirmation_code }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Enrollment Date</h5>
                                <p>{{ $management->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Name</h5>
                                <p>{{ $management->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Email</h5>
                                <p>{{ $management->email }}</p>
                            </div>
                        </div>
                        
                        @if($management->phone)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Phone</h5>
                                <p>{{ $management->phone }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($management->medications)
                        <div class="mb-4">
                            <h5>Current Medications</h5>
                            <p>{{ $management->medications }}</p>
                        </div>
                        @endif
                        
                        @if($management->services)
                        <div class="mb-4">
                            <h5>Services</h5>
                            <div>
                                @foreach($management->services as $service)
                                    <span class="badge bg-primary mb-1">{{ str_replace('_', ' ', ucfirst($service)) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if($management->special_requests)
                        <div class="mb-4">
                            <h5>Special Requests</h5>
                            <p>{{ $management->special_requests }}</p>
                        </div>
                        @endif
                        
                        <div class="alert alert-info mb-4">
                            <h5 class="alert-heading">Next Steps</h5>
                            <p class="mb-0">A pharmacist will contact you within 24-48 hours to discuss your medication management plan and answer any questions you may have.</p>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pharmacy.my-medication-management') }}" class="btn btn-secondary">Back to My Enrollments</a>
                            
                            @if($management->status != 'cancelled')
                                <form action="{{ route('pharmacy.medication-management.cancel', $management->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this enrollment?')">Cancel Enrollment</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
