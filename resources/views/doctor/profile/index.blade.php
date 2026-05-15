@extends('doctor.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Doctor Profile</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('doctor.profile.update') }}" method="POST" class="mb-4">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Specialty</label>
                                    <input type="text" name="specialty" class="form-control @error('specialty') is-invalid @enderror" 
                                           value="{{ old('specialty', $profile->specialty ?? '') }}">
                                    @error('specialty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Years of Experience</label>
                                    <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" 
                                           value="{{ old('experience_years', $profile->experience_years ?? '') }}">
                                    @error('experience_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Qualification</label>
                                    <input type="text" name="qualification" class="form-control @error('qualification') is-invalid @enderror" 
                                           value="{{ old('qualification', $profile->qualification ?? '') }}">
                                    @error('qualification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Consultation Fee</label>
                                    <input type="number" step="0.01" name="consultation_fee" class="form-control @error('consultation_fee') is-invalid @enderror" 
                                           value="{{ old('consultation_fee', $profile->consultation_fee ?? '') }}">
                                    @error('consultation_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>About</label>
                            <textarea name="about" class="form-control @error('about') is-invalid @enderror" rows="3">{{ old('about', $profile->about ?? '') }}</textarea>
                            @error('about')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $profile->phone ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                           value="{{ old('address', $profile->address ?? '') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>

                    <!-- Change Password Form -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Change Password</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('doctor.profile.change-password') }}" method="POST">
                                @csrf
                                
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
