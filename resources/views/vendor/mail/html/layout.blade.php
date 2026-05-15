<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicare - Contact Message</title>
    <style>
        /* Add your email styles here */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            padding: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Medicare - Contact Message</h1>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} Medicare. All rights reserved.</p>
    </div>
</body>
</html>
