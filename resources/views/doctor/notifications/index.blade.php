@extends('doctor.layouts.app')

@section('title', 'User Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Notifications</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Send Notification</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.user-notifications.send') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Patient</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Notification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Notifications</h6>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Sent At</th>
                                        <th>Read At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $notification)
                                        @php
                                            $data = json_decode($notification->data);
                                        @endphp
                                        <tr>
                                            <td>{{ $notification->notifiable->name ?? 'Unknown' }}</td>
                                            <td>{{ $data->title ?? 'N/A' }}</td>
                                            <td>{{ $data->message ?? 'N/A' }}</td>
                                            <td>{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                @if($notification->read_at)
                                                    <span class="badge badge-success">Read at {{ $notification->read_at->format('M d, Y h:i A') }}</span>
                                                @else
                                                    <span class="badge badge-warning">Unread</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <p>No notifications found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
