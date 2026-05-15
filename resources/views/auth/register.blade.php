@extends('layouts.app')

@section('additional_styles')
<style>
    .register-container {
        max-width: 600px;
        margin: 50px auto;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .register-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .register-header h2 {
        color: var(--primary-color);
        font-weight: bold;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .register-form .form-control {
        padding: 12px;
        border-radius: 5px;
        height: auto;
    }
    
    .register-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        width: 100%;
        font-weight: bold;
        margin-top: 10px;
    }
    
    .register-btn:hover {
        background-color: var(--secondary-color);
    }
    
    .register-footer {
        text-align: center;
        margin-top: 20px;
    }
    
    .register-footer a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .register-footer a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="register-container">
                <div class="register-header">
                    <h2>{{ __('Create an Account') }}</h2>
                    <p class="text-muted">Join our healthcare platform to access all services</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="register-form">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Full Name') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your full name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email address">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Create a password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="Enter your phone number">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">{{ __('Address') }}</label>
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required placeholder="Enter your address">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">{{ __('Role') }}</label>
                        <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required>
                            <option value="user">Patient</option>
                            <option value="doctor">Doctor</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn register-btn">
                            {{ __('Register') }}
                        </button>
                    </div>

                    <div class="register-footer">
                        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection