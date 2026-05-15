@extends('layouts.doctor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Messages</h4>
                        <a href="{{ route('doctor.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

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
                        <!-- Conversation List -->
                        <div class="col-md-4">
                            <div class="list-group">
                                @forelse($users as $user)
                                    <a href="{{ route('doctor.messages.conversation', $user->id) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->is('*conversation/' . $user->id) ? 'active' : '' }}">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                                                 class="rounded-circle me-3" width="50" height="50" alt="{{ $user->name }}">
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">
                                                    @if($user->last_message)
                                                        {{ Str::limit($user->last_message->content, 30) }}
                                                    @else
                                                        No messages yet
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        @if($user->unread_count > 0)
                                            <span class="badge bg-primary rounded-pill">{{ $user->unread_count }}</span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <p class="mb-0">No conversations yet</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Conversation Area -->
                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <i class="fas fa-comment-alt fa-4x text-muted mb-3"></i>
                                        <h5>Select a conversation to start messaging</h5>
                                        <p class="text-muted">Or start a new conversation with a patient</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .list-group-item.active * {
        color: white !important;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0 !important;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .unread {
        background-color: #f8f9fa;
    }
</style>
@endpush
