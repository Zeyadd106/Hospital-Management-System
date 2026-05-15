@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Our Departments</h1>
    
    <div class="row">
        @forelse($departments as $department)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $department->name }}</h5>
                        <p class="card-text">{{ $department->description }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('doctors.index', ['department' => $department->id]) }}" class="btn btn-primary">View Doctors</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No departments are currently available. Please check back later.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

