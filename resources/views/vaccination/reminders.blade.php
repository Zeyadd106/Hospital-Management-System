@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upcoming Vaccinations</h3>
                    <div class="card-tools">
                        <a href="{{ route('vaccination.book.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Book New Vaccination
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($upcomingVaccinations->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No upcoming vaccinations.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vaccine Type</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingVaccinations as $vaccination)
                                        <tr>
                                            <td>{{ $vaccination->vaccine->name }}</td>
                                            <td>{{ $vaccination->date }}</td>
                                            <td>{{ $vaccination->time }}</td>
                                            <td>{{ $vaccination->clinic->name }}</td>
                                            <td>
                                                <span class="badge bg-warning">Upcoming</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="sendReminder('{{ route('vaccination.reminders.send', $vaccination->id) }}')">
                                                    <i class="fas fa-bell"></i> Reminder
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function sendReminder(url) {
    if (confirm('Send reminder for this vaccination?')) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Reminder sent successfully!');
                } else {
                    alert('Failed to send reminder');
                }
            },
            error: function(xhr) {
                alert('Error sending reminder');
            }
        });
    }
}
</script>
@endpush
@endsection
