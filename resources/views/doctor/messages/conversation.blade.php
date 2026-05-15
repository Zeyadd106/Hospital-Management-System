@extends('layouts.doctor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('doctor.messages.index') }}" class="btn btn-sm btn-outline-secondary me-2 d-md-none">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                                 class="rounded-circle me-2" width="40" height="40" alt="{{ $user->name }}">
                            <div>
                                <h5 class="mb-0">{{ $user->name }}</h5>
                                <small class="text-muted">
                                    @if(method_exists($user, 'isOnline') && $user->isOnline())
                                        <span class="text-success">Online</span>
                                    @else
                                        @if($user->last_seen)
                                            Last seen {{ $user->last_seen->diffForHumans() }}
                                        @else
                                            Offline
                                        @endif
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">View Profile</a></li>
                            <li><a class="dropdown-item" href="#">Call</a></li>
                            <li><a class="dropdown-item" href="#">Video Call</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#">Block User</a></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body chat-container" id="chatContainer">
                    <div class="chat-messages">
                        @if($messages->count() > 0)
                            @foreach($messages as $message)
                                <div class="message {{ $message->is_from_user ? 'received' : 'sent' }}">
                                    <div class="message-content">
                                        <p class="mb-0">{{ $message->content }}</p>
                                        <small class="text-muted">
                                            {{ $message->created_at->format('h:i A') }}
                                            @if($message->read_at)
                                                <i class="fas fa-check-double text-primary ms-1"></i>
                                            @else
                                                <i class="fas fa-check text-muted ms-1"></i>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                <p class="mb-0">No messages yet. Start the conversation!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer bg-white">
                    <form id="messageForm" action="{{ route('doctor.messages.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Attach file">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input type="text" name="content" class="form-control" placeholder="Type a message..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .chat-container {
        height: 60vh;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
    }
    
    .chat-messages {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .message {
        max-width: 70%;
        margin-bottom: 1rem;
    }
    
    .message.sent {
        align-self: flex-end;
    }
    
    .message.received {
        align-self: flex-start;
    }
    
    .message-content {
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        position: relative;
    }
    
    .sent .message-content {
        background-color: #0d6efd;
        color: white;
        border-bottom-right-radius: 0.25rem;
    }
    
    .received .message-content {
        background-color: #e9ecef;
        border-bottom-left-radius: 0.25rem;
    }
    
    .message small {
        font-size: 0.7rem;
        opacity: 0.8;
    }
    
    .sent small {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-scroll to bottom of chat
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
    
    // Handle form submission with AJAX
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Clear the input
                form.querySelector('input[name="content"]').value = '';
                
                // Add the new message to the chat
                const messagesContainer = document.querySelector('.chat-messages');
                const message = data.message;
                
                const messageHtml = `
                    <div class="message sent">
                        <div class="message-content">
                            <p class="mb-0">${message.content}</p>
                            <small class="text-muted">
                                ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                                <i class="fas fa-check text-muted ms-1"></i>
                            </small>
                        </div>
                    </div>
                `;
                
                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    
    // Poll for new messages every 5 seconds
    setInterval(function() {
        const lastMessage = document.querySelector('.message:last-child');
        const lastMessageTime = lastMessage ? lastMessage.dataset.timestamp : 0;
        
        fetch(`{{ route('doctor.messages.conversation', $user->id) }}?last_message=${lastMessageTime}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    const messagesContainer = document.querySelector('.chat-messages');
                    
                    data.messages.forEach(message => {
                        const messageHtml = `
                            <div class="message received">
                                <div class="message-content">
                                    <p class="mb-0">${message.content}</p>
                                    <small class="text-muted">
                                        ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                                    </small>
                                </div>
                            </div>
                        `;
                        
                        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    });
                    
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                }
            });
    }, 5000);
</script>
@endpush
