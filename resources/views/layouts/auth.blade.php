<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Medicare') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 400px;
            margin: 0 auto;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h1 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            padding: 1rem;
            border-radius: 10px;
            width: 100%;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        .btn-link {
            color: #4f46e5;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .social-auth {
            margin-top: 2rem;
            text-align: center;
        }

        .social-auth h5 {
            color: #666;
            margin-bottom: 1rem;
        }

        .social-btn {
            width: 100%;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .social-btn i {
            font-size: 1.2rem;
        }

        .btn-google {
            background-color: #fff;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-google:hover {
            background-color: #f8f9fa;
        }

        .btn-facebook {
            background-color: #1877f2;
            color: white;
        }

        .btn-facebook:hover {
            background-color: #166fe0;
        }

        .form-check-input {
            margin-top: 0.3rem;
        }

        .form-check-label {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>{{ config('app.name', 'Medicare') }}</h1>
            <p>Welcome back! Please login to continue.</p>
        </div>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
