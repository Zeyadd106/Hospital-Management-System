@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Messages</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">All Messages</h6>
                                <small class="text-muted">{{ $messages->count() }} total</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Unread Messages</h6>
                                <small class="text-muted">{{ $messages->where('read_at', null)->count() }}</small>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Read Messages</h6>
                                <small class="text-muted">{{ $messages->whereNotNull('read_at')->count() }}</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">New Message</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient" class="form-label">To</label>
                            <select name="recipient" id="recipient" class="form-select @error('recipient') is-invalid @enderror" required>
                                <option value="">Select recipient</option>
                                @foreach($recipients as $recipient)
                                    <option value="{{ $recipient->id }}" {{ old('recipient') == $recipient->id ? 'selected' : '' }}>
                                        {{ $recipient->name }} ({{ $recipient->role }})
                                    </option>
                                @endforeach
                            </select>
                            @error('recipient')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" id="subject" 
                                class="form-control @error('subject') is-invalid @enderror" 
                                value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" 
                                class="form-control @error('message') is-invalid @enderror" 
                                rows="3" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Message List</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            Sort By
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?sort=date">Date</a></li>
                            <li><a class="dropdown-item" href="?sort=sender">Sender</a></li>
                            <li><a class="dropdown-item" href="?sort=subject">Subject</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    @if($messages->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-envelope-open-text fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No messages found</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr class="{{ $message->read_at ? '' : 'table-info' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $message->sender->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                                                         alt="{{ $message->sender->name }}" 
                                                         class="rounded-circle me-2" 
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                    <span>{{ $message->sender->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('messages.show', $message->id) }}" class="text-decoration-none">
                                                    {{ $message->subject }}
                                                </a>
                                            </td>
                                            <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $message->read_at ? 'secondary' : 'primary' }}">
                                                    {{ $message->read_at ? 'Read' : 'Unread' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('messages.show', $message->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete('{{ $message->id }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(messageId) {
    if (confirm('Are you sure you want to delete this message?')) {
        window.location.href = `/messages/${messageId}/delete`;
    }
}

// Mark message as read when viewing
if (window.location.pathname.includes('/messages/')) {
    const messageId = window.location.pathname.split('/').pop();
    if (messageId && !window.location.pathname.includes('/delete')) {
        fetch(`/messages/${messageId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }
}
</script>
@endsection
