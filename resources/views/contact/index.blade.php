@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Contact Us') }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" id="contactForm">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="message_type" class="form-label">{{ __('Message Type') }}</label>
                            <select name="message_type" id="message_type" class="form-select" required>
                                <option value="">{{ __('Select Message Type') }}</option>
                                <option value="admin_support">{{ __('Admin Support') }}</option>
                                <option value="doctor_consultation">{{ __('Doctor Consultation') }}</option>
                            </select>
                        </div>

                        <div id="departmentSection" class="form-group mb-3" style="display:none;">
                            <label for="department" class="form-label">{{ __('Select Department') }}</label>
                            <select name="department" id="department" class="form-select">
                                <option value="">{{ __('Choose Department') }}</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="doctorSection" class="form-group mb-3" style="display:none;">
                            <label for="doctor_id" class="form-label">{{ __('Select Doctor') }}</label>
                            <select name="doctor_id" id="doctor_id" class="form-select" disabled>
                                <option value="">{{ __('Select a Doctor') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" class="form-control" required 
                                   value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                   value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">{{ __('Phone (Optional)') }}</label>
                            <input type="tel" name="phone" id="phone" class="form-control"
                                   value="{{ old('phone') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject" class="form-label">{{ __('Subject') }}</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="message" class="form-label">{{ __('Message') }}</label>
                            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Send Message') }}
                            </button>
                        </div>
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
    const messageTypeSelect = document.getElementById('message_type');
    const departmentSection = document.getElementById('departmentSection');
    const doctorSection = document.getElementById('doctorSection');
    const departmentSelect = document.getElementById('department');
    const doctorSelect = document.getElementById('doctor_id');

    messageTypeSelect.addEventListener('change', function() {
        if (this.value === 'doctor_consultation') {
            departmentSection.style.display = 'block';
            doctorSection.style.display = 'none';
            doctorSelect.disabled = true;
        } else {
            departmentSection.style.display = 'none';
            doctorSection.style.display = 'none';
            doctorSelect.disabled = true;
        }
    });

    departmentSelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/contact/doctors/${this.value}`)
                .then(response => response.json())
                .then(doctors => {
                    doctorSelect.innerHTML = '<option value="">Select a Doctor</option>';
                    doctors.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = `Dr. ${doctor.first_name} ${doctor.last_name} - ${doctor.specialization}`;
                        doctorSelect.appendChild(option);
                    });
                    doctorSection.style.display = 'block';
                    doctorSelect.disabled = false;
                });
        } else {
            doctorSection.style.display = 'none';
            doctorSelect.disabled = true;
        }
    });
});
</script>
@endsection
