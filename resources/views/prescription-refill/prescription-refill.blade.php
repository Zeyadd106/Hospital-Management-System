@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Request Prescription Refill</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pharmacy.prescription-refill.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="prescription_number">Prescription Number</label>
                            <input type="text" class="form-control" id="prescription_number" name="prescription_number" required>
                        </div>

                        <div class="form-group">
                            <label for="patient_name">Patient Name</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                        </div>

                        <div class="form-group">
                            <label for="patient_dob">Patient Date of Birth</label>
                            <input type="date" class="form-control" id="patient_dob" name="patient_dob" required>
                        </div>

                        <div class="form-group">
                            <label for="medication_name">Medication Name</label>
                            <input type="text" class="form-control" id="medication_name" name="medication_name" required>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="delivery_requested" name="delivery_requested">
                                <label class="form-check-label" for="delivery_requested">
                                    Request Delivery
                                </label>
                            </div>
                        </div>

                        <div class="delivery-address-section" style="display: none;">
                            <div class="form-group">
                                <label for="delivery_address">Delivery Address</label>
                                <textarea class="form-control" id="delivery_address" name="delivery_address" rows="3"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Refill Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deliveryCheckbox = document.getElementById('delivery_requested');
        const deliveryAddressSection = document.querySelector('.delivery-address-section');
        
        deliveryCheckbox.addEventListener('change', function() {
            if (this.checked) {
                deliveryAddressSection.style.display = 'block';
            } else {
                deliveryAddressSection.style.display = 'none';
            }
        });
    });
</script>
@endsection

