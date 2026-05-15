@extends('layouts.app')

@section('additional_styles')
<style>
    .chat-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin: 30px 0;
        overflow: hidden;
    }
    
    .chat-header {
        background-color: var(--primary-color);
        color: white;
        padding: 20px;
        text-align: center;
    }
    
    .chat-body {
        padding: 30px;
        min-height: 400px;
    }
    
    .doctor-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .doctor-card {
        background-color: var(--background-light);
        border-radius: 10px;
        padding: 20px;
        width: calc(50% - 10px);
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }
    
    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--accent-color);
    }
    
    .doctor-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }
    
    .doctor-info h4 {
        margin: 0 0 5px;
        color: var(--primary-color);
    }
    
    .doctor-info p {
        margin: 0;
        color: var(--secondary-color);
    }
    
    .doctor-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 12px;
        margin-top: 5px;
    }
    
    .status-online {
        background-color: #d4edda;
        color: #155724;
    }
    
    .contact-options {
        background-color: var(--background-light);
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .contact-options h4 {
        margin-top: 0;
        color: var(--primary-color);
    }
    
    @media (max-width: 768px) {
        .doctor-card {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3">Chat with Our Doctors</h2>
            <p class="text-muted mb-4">Select a doctor to start a conversation about your health concerns.</p>
            
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading mb-1">Chat System Maintenance</h5>
                        <p class="mb-0">Our chat system is currently undergoing maintenance. For immediate assistance, please use our <a href="{{ route('contact.index') }}" class="alert-link">contact form</a> or call us at (123) 456-7890.</p>
                    </div>
                </div>
            </div>
            
            <div class="chat-container">
                <div class="chat-header">
                    <h3 class="mb-0">Available Healthcare Professionals</h3>
                </div>
                <div class="chat-body">
                    <div class="doctor-list">
                        <div class="doctor-card">
                            <img src="{{ asset('images/doctor.avif') }}" alt="Dr. Simon" class="doctor-avatar">
                            <div class="doctor-info">
                                <h4>Dr. Simon</h4>
                                <p>Neurologist</p>
                                <span class="doctor-status status-online">Available</span>
                            </div>
                        </div>
                        
                        <div class="doctor-card">
                            <img src="{{ asset('images/doctor2.avif') }}" alt="Dr. Michael" class="doctor-avatar">
                            <div class="doctor-info">
                                <h4>Dr. Michael</h4>
                                <p>Cardiologist</p>
                                <span class="doctor-status status-online">Available</span>
                            </div>
                        </div>
                        
                        <div class="doctor-card">
                            <img src="{{ asset('images/doctor3.avif') }}" alt="Dr. Lou" class="doctor-avatar">
                            <div class="doctor-info">
                                <h4>Dr. Lou</h4>
                                <p>Pediatrician</p>
                                <span class="doctor-status status-online">Available</span>
                            </div>
                        </div>
                        
                        <div class="doctor-card">
                            <img src="{{ asset('images/doctor4.jpg') }}" alt="Dr. Ashton Lee" class="doctor-avatar">
                            <div class="doctor-info">
                                <h4>Dr. Ashton Lee</h4>
                                <p>Dermatologist</p>
                                <span class="doctor-status status-online">Available</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-options">
                        <h4>Other Ways to Reach Us</h4>
                        <p>If you prefer to send a detailed message or have a non-urgent inquiry, you can use our contact form.</p>
                        <a href="{{ route('contact.index') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-paper-plane me-2"></i> Go to Contact Form
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event to doctor cards
        const doctorCards = document.querySelectorAll('.doctor-card');
        doctorCards.forEach(card => {
            card.addEventListener('click', function() {
                // Show maintenance message
                alert('Our chat system is currently undergoing maintenance. Please use our contact form for assistance.');
                // Redirect to contact form
                window.location.href = "{{ route('contact.index') }}";
            });
        });
    });
</script>
@endsection

