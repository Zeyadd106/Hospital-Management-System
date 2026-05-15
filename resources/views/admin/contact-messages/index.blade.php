@extends('layouts.admin')

@section('content')
<div class="container-fluid">
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
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Received') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($messages as $message)
                                        <tr>
                                            <td>{{ $message->name }}</td>
                                            <td>{{ $message->email }}</td>
                                            <td>{{ $message->subject }}</td>
                                            <td>
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
                                            </td>
                                            <td>{{ $message->created_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.contact-messages.show', $message->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
