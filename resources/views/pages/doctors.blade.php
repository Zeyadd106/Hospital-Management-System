@extends('layouts.app')

@section('additional_styles')
<style>
    .card img {
        border-radius: 50%;
        margin-bottom: 15px;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .card-text {
        font-size: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="row">
        @foreach($doctors as $doctor)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <img src="{{ $doctor->avatar ? asset($doctor->avatar) : asset('images/default-avatar.png') }}" 
                         alt="{{ $doctor->name }}" 
                         class="rounded-circle mb-3" 
                         style="width: 100px; height: 100px; object-fit: cover;">
                    <h5 class="card-title">{{ $doctor->name }}</h5>
                    <p class="card-text text-muted">{{ $doctor->specialty }}</p>
                    <p class="card-text text-muted">{{ $doctor->department }}</p>
                    <div class="mt-3">
                        <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-primary me-2">
                            View Profile
                        </a>
                        @auth
                            <a href="{{ route('doctor.messages.index') }}" class="btn btn-secondary">
                                Chat
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
