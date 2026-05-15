@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notification Details</h5>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-secondary">Back to Notifications</a>
                </div>

                <div class="card-body">
                    <div class="notification-details">
                        <h4>{{ $notification->title }}</h4>
                        <div class="text-muted mb-3">
                            <small>{{ $notification->created_at->format('F d, Y h:i A') }}</small>
                            @if($notification->is_read)
                                <span class="badge bg-success ms-2">Read</span>
                            @else
                                <span class="badge bg-primary ms-2">Unread</span>
                            @endif
                        </div>
                        
                        <div class="notification-content border-top pt-3">
                            <p>{{ $notification->content }}</p>
                        </div>
                        
                        @if($notification->related_id)
                            <div class="mt-4">
                                <p><strong>Related to:</strong>
                                @if($notification->type == 'appointment')
                                    <a href="{{ route('appointments.show', $notification->related_id) }}">View Appointment</a>
                                @elseif($notification->type == 'prescription')
                                    <a href="{{ route('user.pharmacy.prescriptions.show', $notification->related_id) }}">View Prescription</a>
                                @elseif($notification->type == 'vaccination')
                                    <a href="{{ route('vaccination.booking.show', $notification->related_id) }}">View Vaccination</a>
                                @elseif($notification->type == 'health_screening')
                                    <a href="{{ route('health-screenings.booking.show', $notification->related_id) }}">View Health Screening</a>
                                @endif
                                </p>
                            </div>
                        @endif
                        
                        <div class="d-flex mt-4">
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Mark as Read</button>
                                </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this notification?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
