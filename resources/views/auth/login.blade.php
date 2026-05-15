@extends('layouts.app')

@section('additional_styles')
<style>
    .login-container {
        max-width: 500px;
        margin: 50px auto;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .login-header h2 {
        color: var(--primary-color);
        font-weight: bold;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-control {
        padding: 12px;
        border-radius: 5px;
        height: auto;
    }
    
    .login-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 5px;
        width: 100%;
        font-weight: bold;
        margin-top: 10px;
    }
    
    .login-btn:hover {
        background-color: var(--secondary-color);
    }
    
    .login-footer {
        text-align: center;
        margin-top: 20px;
    }
    
    .social-auth {
        margin-top: 30px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .social-auth h5 {
        margin-bottom: 15px;
        color: #666;
    }
    
    .social-btn {
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .social-btn i {
        margin-right: 10px;
    }
    
    .btn-google {
        background-color: #DB4437;
        color: white;
    }
    
    .btn-google:hover {
        background-color: #C53929;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="login-container">
                <div class="login-header">
                    <h2>{{ __('Login') }}</h2>
                    <p class="text-muted">Welcome back! Please enter your credentials to access your account.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn login-btn">
                            {{ __('Login') }}
                        </button>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a class="text-decoration-none" href="{{ route('register') }}">
                                {{ __('Need an account? Register') }}
                            </a>
                        @endif
                    </div>
                </form>

                <div class="social-auth">
                    <h5>{{ __('Or login with') }}</h5>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('auth.redirect', 'google') }}" class="btn btn-google social-btn">
                            <i class="fab fa-google"></i> {{ __('Google') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection