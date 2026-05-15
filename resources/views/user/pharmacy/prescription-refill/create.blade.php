@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Request Prescription Refill</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.pharmacy.prescription-refill.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="medication_id" class="form-label">Medication</label>
                            <select name="medication_id" id="medication_id" class="form-select" required>
                                <option value="">Select a medication</option>
                                @foreach($medications as $medication)
                                    <option value="{{ $medication->id }}">
                                        {{ $medication->name }} - ${{ number_format($medication->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" 
                                   min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="prescription_image" class="form-label">Prescription Image</label>
                            <input type="file" name="prescription_image" id="prescription_image" class="form-control" 
                                   accept="image/jpeg,image/png,image/gif" required>
                            <div class="form-text">Please upload a clear image of your prescription</div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane me-2"></i>Submit Refill Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
