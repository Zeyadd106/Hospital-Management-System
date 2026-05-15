@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Messages with') }} Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h4>
                    <div>
                        <a href="{{ route('user.messages.index') }}" class="btn btn-secondary btn-sm me-2">
                            {{ __('Back to Message List') }}
                        </a>
                        <a href="{{ route('user.messages.list_doctors') }}" class="btn btn-primary btn-sm">
                            {{ __('Start New Conversation') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="message-container" style="max-height: 500px; overflow-y: auto;">
                        @foreach($messages as $message)
                            <div class="message mb-3 {{ $message->is_from_user ? 'text-end' : 'text-start' }}">
                                <div class="{{ $message->is_from_user ? 'bg-primary text-white' : 'bg-light' }} p-2 rounded d-inline-block">
                                    <p class="mb-1">{{ $message->content }}</p>
                                    <small class="{{ $message->is_from_user ? 'text-white-50' : 'text-muted' }}">
                                        {{ $message->created_at->format('M d, Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('user.messages.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="4" placeholder="{{ __('Type your message...') }}" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">{{ __('Send Message') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-scroll to the bottom of messages
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.querySelector('.message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });
</script>
@endsection
