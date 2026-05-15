@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Prescription Refills</h1>
            
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Medication</th>
                                <th>Status</th>
                                <th>Requested At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($refills as $refill)
                            <tr>
                                <td>{{ $refill->user->name ?? 'Unknown Patient' }}</td>
                                <td>{{ $refill->medication->name ?? 'Unknown Medication' }}</td>
                                <td>
                                    <span class="badge 
                                        @if($refill->status == 'pending') bg-warning
                                        @elseif($refill->status == 'approved') bg-success
                                        @elseif($refill->status == 'rejected') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($refill->status) }}
                                    </span>
                                </td>
                                <td>{{ $refill->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($refill->status == 'pending')
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pharmacy.prescription-refill.approve', $refill->id) }}" 
                                           class="btn btn-sm btn-success">Approve</a>
                                        <a href="{{ route('pharmacy.prescription-refill.reject', $refill->id) }}" 
                                           class="btn btn-sm btn-danger">Reject</a>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{ $refills->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
