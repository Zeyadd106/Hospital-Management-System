@extends('admin.layouts.app')

@section('title', 'Reply to Message')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-reply me-2"></i>Reply to Message: {{ $message->subject }}</h4>
        <a href="{{ route('admin.messages.show', $message->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Message
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <p><strong>Sender Name:</strong> {{ $message->name }}</p>
                <p><strong>Email:</strong> {{ $message->email }}</p>
                <p><strong>Subject:</strong> {{ $message->subject }}</p>
                <p><strong>Original Message:</strong></p>
                <div class="border p-3 bg-light rounded">
                    {{ $message->message }}
                </div>
            </div>

            <form action="{{ route('admin.messages.send-reply', $message->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="reply_subject" class="form-label">Reply Subject</label>
                    <input type="text" class="form-control {{ $errors->has('reply_subject') ? 'is-invalid' : '' }}" id="reply_subject" name="reply_subject" value="{{ old('reply_subject', 'Re: ' . $message->subject) }}" required>
                    @if($errors->has('reply_subject'))
                        <div class="invalid-feedback">{{ $errors->first('reply_subject') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="reply_message" class="form-label">Reply Message</label>
                    <textarea class="form-control {{ $errors->has('reply_message') ? 'is-invalid' : '' }}" id="reply_message" name="reply_message" rows="5" required>{{ old('reply_message') }}</textarea>
                    @if($errors->has('reply_message'))
                        <div class="invalid-feedback">{{ $errors->first('reply_message') }}</div>
                    @endif
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.messages.show', $message->id) }}" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-custom">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection