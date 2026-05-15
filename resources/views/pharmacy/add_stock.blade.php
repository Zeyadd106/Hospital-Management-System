@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Stock for Medication') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <h4>Medication Details</h4>
                        <p><strong>Name:</strong> {{ $medication->name }}</p>
                        <p><strong>Generic Name:</strong> {{ $medication->generic_name ?? 'N/A' }}</p>
                        <p><strong>Manufacturer:</strong> {{ $medication->manufacturer }}</p>
                        
                        @if($medication->stock && $medication->stock->count() > 0)
                            <div class="alert alert-info">
                                <strong>Current Stock:</strong> 
                                {{ $medication->stock->sum('quantity') }} units
                            </div>
                        @else
                            <div class="alert alert-warning">
                                No existing stock for this medication
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('pharmacy.addStock', $medication->id) }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="quantity" class="col-md-4 col-form-label text-md-right">{{ __('Quantity to Add') }}</label>
                            <div class="col-md-6">
                                <input id="quantity" type="number" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       name="quantity" 
                                       value="{{ old('quantity') }}" 
                                       required 
                                       min="1">

                                @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="batch_number" class="col-md-4 col-form-label text-md-right">{{ __('Batch Number') }}</label>
                            <div class="col-md-6">
                                <input id="batch_number" type="text" 
                                       class="form-control @error('batch_number') is-invalid @enderror" 
                                       name="batch_number" 
                                       value="{{ old('batch_number') }}" 
                                       required>

                                @error('batch_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="expiry_date" class="col-md-4 col-form-label text-md-right">{{ __('Expiry Date') }}</label>
                            <div class="col-md-6">
                                <input id="expiry_date" type="date" 
                                       class="form-control @error('expiry_date') is-invalid @enderror" 
                                       name="expiry_date" 
                                       value="{{ old('expiry_date') }}" 
                                       required 
                                       min="{{ now()->addDay()->format('Y-m-d') }}">

                                @error('expiry_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="purchase_price" class="col-md-4 col-form-label text-md-right">{{ __('Purchase Price') }}</label>
                            <div class="col-md-6">
                                <input id="purchase_price" type="number" 
                                       class="form-control @error('purchase_price') is-invalid @enderror" 
                                       name="purchase_price" 
                                       value="{{ old('purchase_price') }}" 
                                       required 
                                       step="0.01" 
                                       min="0">

                                @error('purchase_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="selling_price" class="col-md-4 col-form-label text-md-right">{{ __('Selling Price') }}</label>
                            <div class="col-md-6">
                                <input id="selling_price" type="number" 
                                       class="form-control @error('selling_price') is-invalid @enderror" 
                                       name="selling_price" 
                                       value="{{ old('selling_price') }}" 
                                       required 
                                       step="0.01" 
                                       min="0">

                                @error('selling_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add Stock') }}
                                </button>
                                <a href="{{ route('pharmacy.medications.index') }}" class="btn btn-secondary ml-2">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
