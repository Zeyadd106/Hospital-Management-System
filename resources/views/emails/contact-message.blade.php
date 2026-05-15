@extends('vendor.mail.html.layout')

@section('content')
    <h2>Hello Doctor,</h2>

    <p>You have received a new contact message:</p>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">Name:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $contactMessage->name }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">Email:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $contactMessage->email }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">Phone:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $contactMessage->phone }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;">Subject:</td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $contactMessage->subject }}</td>
        </tr>
    </table>

    <p><strong>Message:</strong></p>
    <p>{{ $contactMessage->message }}</p>

    <p><a href="{{ url('/admin/messages') }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">View Message</a></p>

    <p>Thank you for using our service!</p>

    <p>If you have any questions, please reply to this email.</p>
@endsection
