@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Search Results for "{{ $query }}"</h1>

            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('user.pharmacy.medications.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" 
                                   placeholder="Search medications by name, generic name, or manufacturer"
                                   value="{{ $query }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            @if($medications->isEmpty())
                <div class="alert alert-info">
                    No medications found matching your search query.
                </div>
            @else
                <div class="row">
                    @foreach($medications as $medication)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $medication->name }}</h5>
                                    <p class="card-text">
                                        <strong>Generic Name:</strong> {{ $medication->generic_name ?? 'N/A' }}<br>
                                        <strong>Manufacturer:</strong> {{ $medication->manufacturer }}
                                    </p>
                                    
                                    @if($medication->stock && $medication->stock->sum('quantity') > 0)
                                        <span class="badge bg-success">
                                            In Stock: {{ $medication->stock->sum('quantity') }} units
                                        </span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif

                                    <div class="mt-3">
                                        <a href="{{ route('user.pharmacy.medications.show', $medication->id) }}" 
                                           class="btn btn-sm btn-info">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $medications->appends(['query' => $query])->links() }}
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('user.pharmacy.medications.index') }}" class="btn btn-secondary">
                    Back to Medications
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
