@extends('admin.layouts.app')

@section('title', 'Admin Messages')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Messages</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Received At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>{{ $message->id }}</td>
                                <td>{{ $message->name }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>
                                    <span class="badge 
                                        @if($message->status == 'pending') bg-warning 
                                        @elseif($message->status == 'read') bg-info 
                                        @elseif($message->status == 'resolved') bg-success 
                                        @else bg-secondary 
                                        @endif">
                                        {{ ucfirst($message->status) }}
                                    </span>
                                </td>
                                <td>{{ $message->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message->id) }}" class="btn btn-sm btn-info">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No messages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection