@extends('layouts.app')

@section('additional_styles')
<style>
    .success-section {
        padding: 80px 0;
    }
    
    .success-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 40px;
        text-align: center;
    }
    
    .success-icon {
        font-size: 80px;
        color: #28a745;
        margin-bottom: 20px;
    }
    
    .success-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
        color: var(--primary-color);
    }
    
    .success-message {
        margin-bottom: 30px;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="success-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="success-card">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    
                    <h2 class="success-title">Message Sent Successfully!</h2>
                    
                    <p class="success-message">
                        Thank you for contacting us. Your message has been sent to {{ session('doctor') }} regarding "{{ session('subject') }}".
                        We will get back to you as soon as possible.
                    </p>
                    
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary me-2">Back to Home</a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-primary">Send Another Message</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

