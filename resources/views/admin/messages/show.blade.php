@extends('admin.layouts.app')

@section('title', 'Message Details')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-envelope me-2"></i>Message Details #{{ $message->id }}</h4>
        <div>
            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to delete this message?')">
                    <i class="fas fa-trash me-1"></i> Delete Message
                </button>
            </form>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Messages
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID:</strong> {{ $message->id }}</p>
                    <p><strong>From:</strong> 
                        @if($message->is_from_user)
                            {{ $message->user ? $message->user->name : 'User' }} (Patient)
                        @else
                            {{ $message->doctor ? $message->doctor->name : 'Doctor' }} (Doctor)
                        @endif
                    </p>
                    <p><strong>To:</strong> 
                        @if(!$message->is_from_user)
                            {{ $message->user ? $message->user->name : 'User' }} (Patient)
                        @else
                            {{ $message->doctor ? $message->doctor->name : 'Doctor' }} (Doctor)
                        @endif
                    </p>
                    <p><strong>Status:</strong>
                        <span class="badge {{ $message->read_at ? 'badge-success' : 'badge-warning' }}">
                            {{ $message->read_at ? 'Read' : 'Unread' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Sent At:</strong> {{ $message->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Read At:</strong> {{ $message->read_at ? $message->read_at->format('M d, Y H:i') : 'Not read yet' }}</p>
                </div>
            </div>
            <div class="mt-3">
                <p><strong>Message Content:</strong></p>
                <div class="border p-3 bg-light rounded">
                    {{ $message->content }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection