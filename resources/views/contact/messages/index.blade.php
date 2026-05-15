@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Contact Messages') }}</h4>
                </div>
                <div class="card-body">
                    @if($messages->isEmpty())
                        <div class="alert alert-info text-center">
                            {{ __('No contact messages found.') }}
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($messages as $message)
                                <a href="{{ route('contact.messages.show', $message->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $message->subject }}</h5>
                                        <small class="text-muted">
                                            {{ $message->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($message->message, 100) }}</p>
                                    <small class="text-muted">
                                        Status: 
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
                                    </small>
                                </a>
                            @endforeach
                        </div>
                        
                        <div class="mt-3">
                            {{ $messages->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
