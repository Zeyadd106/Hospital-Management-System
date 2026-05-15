<x-layout>
    <x-slot name="title">{{ $doctor->name }} - MediCare</x-slot>
    
    @push('styles')
    <style>
        .doctor-profile-section {
            padding: 4rem 0;
        }
        
        .doctor-image {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .doctor-specialty {
            color: var(--secondary-color);
            font-size: 1.2rem;
        }
        
        .doctor-info {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .contact-info i {
            width: 25px;
            color: var(--primary-color);
        }
        
        .schedule-card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        
        .day-schedule {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .day-schedule:last-child {
            border-bottom: none;
        }
    </style>
    @endpush
    
    <div class="main-content" style="align-items: flex-start;">
        <div class="container py-5">
            <div class="row mb-4">
                <div class="col-12">
                    <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to Doctors
                    </a>
                    <h1>{{ $doctor->name }}</h1>
                    <p class="doctor-specialty">{{ $doctor->specialty }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <img src="{{ asset($doctor->avatar) }}" alt="{{ $doctor->name }}" class="doctor-image mb-4">
                    
                    <div class="doctor-info">
                        <h3>Contact Information</h3>
                        <div class="contact-info">
                            <p><i class="fas fa-envelope"></i> {{ $doctor->email }}</p>
                            <p><i class="fas fa-phone"></i> {{ $doctor->phone }}</p>
                            <p><i class="fas fa-hospital"></i> {{ $doctor->department }} Department</p>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment
                            </a>
                            <a href="{{ route('chat.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-comments me-2"></i>Chat with Doctor
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3>About Dr. {{ explode(' ', $doctor->name)[0] }}</h3>
                            <p>{{ $doctor->bio ?? 'Dr. ' . $doctor->name . ' is a highly skilled ' . strtolower($doctor->specialty) . ' with extensive experience in the field. They are dedicated to providing exceptional care to all patients and staying up-to-date with the latest medical advancements.' }}</p>
                            
                            <h4 class="mt-4">Specializations</h4>
                            <ul>
                                <li>{{ $doctor->specialty }}</li>
                                <li>General Consultations</li>
                                <li>Preventive Care</li>
                                <li>Patient Education</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="schedule-card">
                                <h4 class="mb-3">Working Hours</h4>
                                <div class="day-schedule">
                                    <span>Monday</span>
                                    <span>9:00 AM - 5:00 PM</span>
                                </div>
                                <div class="day-schedule">
                                    <span>Tuesday</span>
                                    <span>9:00 AM - 5:00 PM</span>
                                </div>
                                <div class="day-schedule">
                                    <span>Wednesday</span>
                                    <span>9:00 AM - 5:00 PM</span>
                                </div>
                                <div class="day-schedule">
                                    <span>Thursday</span>
                                    <span>9:00 AM - 5:00 PM</span>
                                </div>
                                <div class="day-schedule">
                                    <span>Friday</span>
                                    <span>9:00 AM - 3:00 PM</span>
                                </div>
                                <div class="day-schedule">
                                    <span>Saturday</span>
                                    <span>10:00 AM - 1:00 PM</span>
                                </div>
                                <div class="day-schedule">
                                    <span>Sunday</span>
                                    <span>Closed</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="schedule-card">
                                <h4 class="mb-3">Education & Training</h4>
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <div class="fw-bold">Medical Degree</div>
                                        <div>University Medical School</div>
                                        <small class="text-muted">2005 - 2009</small>
                                    </li>
                                    <li class="mb-3">
                                        <div class="fw-bold">Residency</div>
                                        <div>General Hospital</div>
                                        <small class="text-muted">2009 - 2012</small>
                                    </li>
                                    <li>
                                        <div class="fw-bold">Fellowship</div>
                                        <div>Specialty Medical Center</div>
                                        <small class="text-muted">2012 - 2014</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <h4>Patient Reviews</h4>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-2">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                </div>
                                <div>4.5 out of 5 (28 reviews)</div>
                            </div>
                            
                            <div class="review-item mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-1">
                                    <div class="fw-bold">Sarah Johnson</div>
                                    <div>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                </div>
                                <p class="mb-1">Dr. {{ explode(' ', $doctor->name)[0] }} was very thorough and took the time to explain everything to me. Highly recommend!</p>
                                <small class="text-muted">3 months ago</small>
                            </div>
                            
                            <div class="review-item">
                                <div class="d-flex justify-content-between mb-1">
                                    <div class="fw-bold">Michael Thompson</div>
                                    <div>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                    </div>
                                </div>
                                <p class="mb-1">Great doctor with excellent bedside manner. Very knowledgeable and caring.</p>
                                <small class="text-muted">1 month ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

