@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('doctor.messages.index') }}" class="btn btn-sm btn-secondary float-end">
                        Back to Conversations
                    </a>
                    Messages with {{ $messages->first()->user->name }}
                </div>
                <div class="card-body">
                    <div class="message-container" style="max-height: 500px; overflow-y: auto;">
                        @foreach($messages as $message)
                            <div class="message mb-3 
                                {{ $message->user_id == Auth::id() ? 'text-end' : 'text-start' }}">
                                <div class="d-inline-block p-2 rounded 
                                    {{ $message->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }}">
                                    {{ $message->content }}
                                    <small class="d-block text-muted 
                                        {{ $message->user_id == Auth::id() ? 'text-white-50' : 'text-muted' }}">
                                        {{ $message->created_at->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="doctor_id" value="{{ $messages->first()->doctor_id }}">
                        <input type="hidden" name="user_id" value="{{ $messages->first()->user_id }}">
                        <div class="input-group">
                            <input type="text" name="content" class="form-control" 
                                   placeholder="Type your message..." required>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to the bottom of messages
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.querySelector('.message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });
</script>
@endpush
