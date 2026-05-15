@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    @if($notifications->count() > 0)
                        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Mark All as Read</button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $notification->is_read ? '' : 'list-group-item-primary' }}">
                                    <div>
                                        <a href="{{ route('notifications.show', $notification->id) }}" class="text-decoration-none">
                                            <h5 class="mb-1">{{ $notification->title }}</h5>
                                            <p class="mb-1">{{ Str::limit($notification->content, 100) }}</p>
                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                    </div>
                                    <div class="d-flex">
                                        @if(!$notification->is_read)
                                            <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Mark as Read</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-bell fa-3x text-muted mb-3"></i>
                            <h5>No notifications yet</h5>
                            <p class="text-muted">You don't have any notifications at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
