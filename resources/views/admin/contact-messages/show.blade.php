@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('Contact Message Details') }}</h4>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary btn-sm">
                        {{ __('Back to Messages') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Name') }}:</strong>
                            {{ $message->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Email') }}:</strong>
                            {{ $message->email }}
                        </div>
                    </div>

                    @if($message->phone)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>{{ __('Phone') }}:</strong>
                            {{ $message->phone }}
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>{{ __('Subject') }}:</strong>
                            {{ $message->subject }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>{{ __('Message') }}:</strong>
                            <p>{{ $message->message }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Status') }}:</strong>
                            @switch($message->status)
                                @case('pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge bg-info">In Progress</span>
                                    @break
                                @case('resolved')
                                    <span class="badge bg-success">Resolved</span>
                                    @break
                            @endswitch
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Submitted') }}:</strong>
                            {{ $message->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <form action="{{ route('admin.messages.update', $message->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $message->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $message->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="resolved" {{ $message->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
