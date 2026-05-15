@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Start a Conversation') }}</h4>
                </div>
                <div class="card-body">
                    @if($doctors->isEmpty())
                        <div class="alert alert-info text-center">
                            {{ __('No doctors available for messaging at the moment.') }}
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($doctors as $doctor)
                                <a href="{{ route('user.messages.show', $doctor->id) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h5>
                                        <small class="text-muted">{{ $doctor->specialization ?? 'General Practice' }}</small>
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
