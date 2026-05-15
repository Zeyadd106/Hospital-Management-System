@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('My Message Threads') }}</h4>
                    <a href="{{ route('user.messages.list_doctors') }}" class="btn btn-primary btn-sm">
                        {{ __('Start New Conversation') }}
                    </a>
                </div>
                <div class="card-body">
                    @if($doctors->isEmpty())
                        <div class="alert alert-info text-center">
                            {{ __('You have no message threads yet. Start a conversation with a doctor!') }}
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($doctors as $doctor)
                                <a href="{{ route('user.messages.show', $doctor->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h5>
                                    </div>
                                    <p class="mb-1">{{ $doctor->email }}</p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
