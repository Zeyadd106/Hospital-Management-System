<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $message->reply_subject ?? 'Reply to Your Message' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1d3557;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .original-message {
            background-color: #f1faee;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #457b9d;
            border-radius: 4px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $message->reply_subject ?? 'Reply to Your Message' }}</h2>
    </div>
    
    <div class="content">
        <p>Dear {{ $name }},</p>
        
        <p>Thank you for contacting us. Here is our response to your inquiry:</p>
        
        <p>{!! nl2br(e($reply)) !!}</p>
        
        <div class="original-message">
            <p><strong>Your original message:</strong></p>
            <p><strong>Subject:</strong> {{ $original_subject }}</p>
            <p>{!! nl2br(e($original_message)) !!}</p>
        </div>
        
        <p>If you have any further questions, please don't hesitate to contact us.</p>
        
        <p>Best regards,<br>
        Medicare Team</p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply directly to this message.</p>
        <p>&copy; {{ date('Y') }} Medicare. All rights reserved.</p>
    </div>
</body>
</html>