<div class="chat-item" data-doctor-id="{{ $doctor->id }}">
    <img src="{{ asset($doctor->avatar) }}" alt="{{ $doctor->name }}" class="avatar">
    <div class="chat-info">
        <p>{{ $doctor->name }}</p>
        <span>{{ $doctor->specialty }}</span>
    </div>
    <div class="chat-meta">
        <span class="chat-time" id="time-{{ $doctor->id }}">{{ $time ?? '' }}</span>
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="unread-badge" id="unread-{{ $doctor->id }}">
                {{ $unreadCount }}
            </span>
        @else
            <span class="unread-badge" id="unread-{{ $doctor->id }}" style="display: none;">0</span>
        @endif
    </div>
</div>

