@extends('layouts.app')

@section('additional_styles')
<style>
    .profile-section {
        padding: 50px 0;
    }
    
    .profile-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .profile-header h2 {
        color: var(--primary-color);
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .profile-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }
    
    .profile-card-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
        color: var(--primary-color);
    }
    
    .profile-info-item {
        margin-bottom: 15px;
    }
    
    .profile-info-label {
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .booking-table {
        width: 100%;
    }
    
    .booking-table th {
        background-color: var(--primary-color);
        color: white;
        padding: 10px;
    }
    
    .booking-table td {
        padding: 10px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .booking-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }
    
    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }
    
    .status-confirmed {
        background-color: #17a2b8;
        color: white;
    }
    
    .status-completed {
        background-color: #28a745;
        color: white;
    }
    
    .status-cancelled {
        background-color: #dc3545;
        color: white;
    }
    
    .nav-tabs .nav-link {
        color: var(--primary-color);
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        font-weight: bold;
        border-bottom: 3px solid var(--primary-color);
    }
</style>
@endsection

@section('content')
<div class="profile-section">
    <div class="container">
        <div class="profile-header">
            <h2>My Profile</h2>
            <p>Manage your personal information and view your bookings</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            <div class="col-lg-4">
                <div class="profile-card">
                    <h3 class="profile-card-title">Personal Information</h3>
                    
                    <div class="profile-info-item">
                        <div class="profile-info-label">Name</div>
                        <div>{{ $user->name }}</div>
                    </div>
                    
                    <div class="profile-info-item">
                        <div class="profile-info-label">Email</div>
                        <div>{{ $user->email }}</div>
                    </div>
                    
                    <div class="profile-info-item">
                        <div class="profile-info-label">Phone</div>
                        <div>{{ $user->phone ?? 'Not provided' }}</div>
                    </div>
                    
                    <div class="profile-info-item">
                        <div class="profile-info-label">Address</div>
                        <div>{{ $user->address ?? 'Not provided' }}</div>
                    </div>
                    
                    <div class="profile-info-item">
                        <div class="profile-info-label">Date of Birth</div>
                        <div>{{ $user->date_of_birth ? date('F d, Y', strtotime($user->date_of_birth)) : 'Not provided' }}</div>
                    </div>
                    
                    <div class="profile-info-item">
                        <div class="profile-info-label">Gender</div>
                        <div>{{ $user->gender ? ucfirst($user->gender) : 'Not provided' }}</div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="profile-card">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="vaccinations-tab" data-bs-toggle="tab" data-bs-target="#vaccinations" type="button" role="tab" aria-controls="vaccinations" aria-selected="true">Vaccinations</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="screenings-tab" data-bs-toggle="tab" data-bs-target="#screenings" type="button" role="tab" aria-controls="screenings" aria-selected="false">Health Screenings</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab" aria-controls="appointments" aria-selected="false">Doctor Appointments</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-4" id="myTabContent">
                        <div class="tab-pane fade show active" id="vaccinations" role="tabpanel" aria-labelledby="vaccinations-tab">
                            @if($vaccinationBookings->count() > 0)
                                <table class="booking-table">
                                    <thead>
                                        <tr>
                                            <th>Vaccination Type</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($vaccinationBookings as $booking)
                                            <tr>
                                                <td>{{ $booking->vaccination_type }}</td>
                                                <td>{{ date('M d, Y', strtotime($booking->appointment_date)) }}</td>
                                                <td>{{ date('h:i A', strtotime($booking->appointment_time)) }}</td>
                                                <td>
                                                    <span class="booking-status status-{{ $booking->status }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('vaccinations.show', $booking->id) }}" class="btn btn-sm btn-info">View</a>
                                                    
                                                    @if($booking->status == 'pending')
                                                        <a href="{{ route('vaccinations.edit', $booking->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                        
                                                        <form action="{{ route('vaccinations.destroy', $booking->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center">
                                    <p>You don't have any vaccination bookings yet.</p>
                                    <a href="{{ route('vaccinations.create') }}" class="btn btn-primary">Book a Vaccination</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="screenings" role="tabpanel" aria-labelledby="screenings-tab">
                            @if($healthScreeningBookings->count() > 0)
                                <table class="booking-table">
                                    <thead>
                                        <tr>
                                            <th>Screening Type</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($healthScreeningBookings as $booking)
                                            <tr>
                                                <td>{{ $booking->screening_type }}</td>
                                                <td>{{ date('M d, Y', strtotime($booking->appointment_date)) }}</td>
                                                <td>{{ date('h:i A', strtotime($booking->appointment_time)) }}</td>
                                                <td>
                                                    <span class="booking-status status-{{ $booking->status }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('health-screenings.show', $booking->id) }}" class="btn btn-sm btn-info">View</a>
                                                    
                                                    @if($booking->status == 'pending')
                                                        <a href="{{ route('health-screenings.edit', $booking->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                        
                                                        <form action="{{ route('health-screenings.destroy', $booking->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center">
                                    <p>You don't have any health screening bookings yet.</p>
                                    <a href="{{ route('health-screenings.create') }}" class="btn btn-primary">Book a Health Screening</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                            @if($appointments->count() > 0)
                                <table class="booking-table">
                                    <thead>
                                        <tr>
                                            <th>Doctor</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                            <tr>
                                                <td>
                                                    {{ $appointment->doctor ? $appointment->doctor->name : 'Doctor Not Assigned' }}
                                                </td>
                                                <td>
                                                    {{ date('M d, Y', strtotime($appointment->appointment_date)) }}
                                                </td>
                                                <td>
                                                    {{ date('h:i A', strtotime($appointment->appointment_time)) }}
                                                </td>
                                                <td>
                                                    <span class="booking-status status-{{ $appointment->status }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info">View</a>
                                                    
                                                    @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
                                                        <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center">
                                    <p>You don't have any doctor appointments yet.</p>
                                    <a href="{{ route('doctors.index') }}" class="btn btn-primary">Book an Appointment</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>
                    
                    <h5 class="mb-3">Change Password (Optional)</h5>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
