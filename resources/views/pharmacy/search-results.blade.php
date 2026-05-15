@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Search Results for "{{ $query }}"</h2>
                </div>
                
                <div class="card-body">
                    @if($medicines->isEmpty())
                        <div class="alert alert-info">
                            No medications found matching your search criteria.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Manufacturer</th>
                                        <th>Strength</th>
                                        <th>Form</th>
                                        <th>Price</th>
                                        <th>Pharmacy</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicines as $medicine)
                                        <tr>
                                            <td>{{ $medicine->name }}</td>
                                            <td>{{ $medicine->manufacturer }}</td>
                                            <td>{{ $medicine->strength }}</td>
                                            <td>{{ $medicine->form }}</td>
                                            <td>${{ number_format($medicine->price, 2) }}</td>
                                            <td>
                                                <a href="{{ route('pharmacy.show', $medicine->pharmacy->id) }}">
                                                    {{ $medicine->pharmacy->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($medicine->status)
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-danger">Not Available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $medicines->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
