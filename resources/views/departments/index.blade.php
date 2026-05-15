@extends('layouts.app')

@section('additional_styles')
<style>
    .departments-section {
        padding: 80px 0;
        background-color: var(--background-light);
    }

    .departments-section h2 {
        font-size: 32px;
        color: #004aad;
        margin-bottom: 40px;
    }

    .department-card {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        height: 350px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .department-card:hover {
        transform: translateY(-10px);
    }

    .department-card .icon {
        font-size: 48px;
        color: #004aad;
        margin-bottom: 10px;
    }

    .department-card h4 {
        font-size: 22px;
        color: #004aad;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .department-card p {
        font-size: 16px;
        color: #333;
        margin-bottom: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
    }

    .department-card a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
    }

    .department-card a:hover {
        color: #0056b3;
    }
</style>
@endsection

@section('content')
<section class="departments-section text-center">
    <div class="container">
        <h2 class="mb-5">Our Departments</h2>   
        <div class="row">
            @forelse($departments as $department)
                <div class="col-md-4">
                    <div class="department-card">
                        <div>
                            <div class="icon">
                                @switch($department->name)
                                    @case('Neurology')
                                        &#129504;
                                        @break
                                    @case('Cardiology')
                                        &#128151;
                                        @break
                                    @case('Surgery')
                                        &#128137;
                                        @break
                                    @case('Gastroenterology')
                                        &#128300;
                                        @break
                                    @case('Ophthalmology')
                                        &#128568;
                                        @break
                                    @case('Pediatrics')
                                        &#128118;
                                        @break
                                    @case('Orthopedics')
                                        &#128170;
                                        @break
                                    @case('Dentistry')
                                        &#128177;
                                        @break
                                    @case('Radiology')
                                        &#128300;
                                        @break
                                    @default
                                        &#127973;
                                @endswitch
                            </div>
                            <h4>{{ $department->name }}</h4>
                            <p>{{ $department->description }}</p>
                        </div>
                        <a href="{{ route('departments.show', $department->id) }}">READ MORE</a>
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
</section>
@endsection
