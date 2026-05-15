@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $pharmacy->name }}</h2>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Pharmacy Information</h5>
                                    <p class="card-text"><strong>Address:</strong> {{ $pharmacy->address }}</p>
                                    <p class="card-text"><strong>Phone:</strong> {{ $pharmacy->phone }}</p>
                                    <p class="card-text"><strong>Email:</strong> {{ $pharmacy->email }}</p>
                                    <p class="card-text"><strong>License Number:</strong> {{ $pharmacy->license_number }}</p>
                                    <p class="card-text"><strong>Status:</strong> 
                                        @if($pharmacy->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Available Medications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Manufacturer</th>
                                                    <th>Strength</th>
                                                    <th>Form</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($medications as $medication)
                                                    <tr>
                                                        <td>{{ $medication->name }}</td>
                                                        <td>{{ $medication->manufacturer }}</td>
                                                        <td>{{ $medication->strength }}</td>
                                                        <td>{{ $medication->form }}</td>
                                                        <td>${{ number_format($medication->price, 2) }}</td>
                                                        <td>
                                                            @if($medication->status)
                                                                <span class="badge bg-success">Available</span>
                                                            @else
                                                                <span class="badge bg-danger">Not Available</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $medications->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Contact Information</h5>
                                    <p class="card-text"><i class="fas fa-phone"></i> {{ $pharmacy->phone }}</p>
                                    <p class="card-text"><i class="fas fa-envelope"></i> {{ $pharmacy->email }}</p>
                                    <p class="card-text"><i class="fas fa-map-marker-alt"></i> {{ $pharmacy->address }}</p>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title">Business Hours</h5>
                                    <p class="card-text">Monday - Friday: 8:00 AM - 8:00 PM</p>
                                    <p class="card-text">Saturday: 9:00 AM - 6:00 PM</p>
                                    <p class="card-text">Sunday: Closed</p>
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
