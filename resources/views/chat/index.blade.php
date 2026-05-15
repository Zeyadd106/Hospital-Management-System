@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Available Doctors</div>
                <div class="card-body">
                    <div class="row">
                        @foreach($doctors as $doctor)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $doctor->name }}</h5>
                                        <p class="card-text">
                                            <strong>Specialization:</strong> {{ $doctor->doctorProfile->specialization }}<br>
                                            <strong>Availability:</strong> 
                                            @if($doctor->doctorProfile->is_available)
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-danger">Not Available</span>
                                            @endif
                                            <br>
                                            <strong>Rating:</strong> {{ $doctor->doctorProfile->rating }} <br>
                                            <strong>Consultation Fee:</strong> ${{ number_format($doctor->doctorProfile->consultation_fee, 2) }}
                                        </p>
                                        <a href="{{ route('chat.with-doctor', $doctor->id) }}" class="btn btn-primary w-100">Chat Now</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
<script>
    let currentDoctor = null;
    let userId = {{ Auth::id() }};
    let userRole = '{{ Auth::user()->role }}';

    // Initialize Pusher Beams
    const beamsClient = new PusherPushNotifications.Client({
        instanceId: 'b5002337-70a7-4520-881e-f6b479db688f',
    });

    // Start the client and register device
    beamsClient.start()
        .then(() => beamsClient.addDeviceInterest(`user.${userId}`))
        .then(() => {
            console.log('Successfully registered and subscribed!');
            // Register device with our backend
            fetch('/notifications/register-device', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    endpoint: beamsClient.getEndpoint(),
                    keys: beamsClient.getKeys(),
                    interests: [userRole]
                })
            });
        })
        .catch(console.error);

    // Handle incoming notifications
    beamsClient.on('push-notification', (notification) => {
        const { title, body } = notification.data;
        displayNotification('push', { title, body });
    });

    // Initialize Pusher
    Echo.private(`user.${userId}`)
        .listen('.message.sent', (e) => {
            if (currentDoctor === e.message.doctor_id) {
                displayMessage(e.message);
            }
        })
        .listen('.appointment.updated', (e) => {
            displayNotification('appointment', e.appointment);
            sendPushNotification('New Appointment', `Your appointment has been updated: ${e.appointment.status}`);
        })
        .listen('.vaccination.updated', (e) => {
            displayNotification('vaccination', e.vaccination);
            sendPushNotification('Vaccination Update', `Your vaccination status has been updated: ${e.vaccination.status}`);
        })
        .listen('.health-screening.updated', (e) => {
            displayNotification('health-screening', e.screening);
            sendPushNotification('Health Screening Update', `Your health screening status has been updated: ${e.screening.status}`);
        });

    function sendPushNotification(title, body) {
        fetch('/notifications/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                title: title,
                body: body,
                interests: [userRole]
            })
        });
    }

    function selectDoctor(doctorId) {
        currentDoctor = doctorId;
        $('#chat-container').removeClass('d-none');
        $('#no-doctor-selected').addClass('d-none');
        loadMessages();
    }

    function loadMessages() {
        if (!currentDoctor) return;
        
        $.ajax({
            url: `/chat/messages/${currentDoctor}`,
            method: 'GET',
            success: function(messages) {
                displayMessages(messages);
            }
        });
    }

    function displayMessages(messages) {
        const container = $('#messages-container');
        container.empty();
        
        messages.forEach(message => {
            const messageDiv = $(`
                <div class="message ${message.is_from_user ? 'user' : 'doctor'}">
                    <p>${message.content}</p>
                    <small class="text-muted">${moment(message.created_at).format('HH:mm')}</small>
                </div>
            `);
            container.append(messageDiv);
        });
        container.scrollTop(container[0].scrollHeight);
    }

    function displayMessage(message) {
        const messageDiv = $(`
            <div class="message ${message.is_from_user ? 'user' : 'doctor'}">
                <p>${message.content}</p>
                <small class="text-muted">${moment(message.created_at).format('HH:mm')}</small>
            </div>
        `);
        $('#messages-container').append(messageDiv);
        $('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);
    }

    function sendMessage() {
        const message = $('#message-input').val().trim();
        if (!message || !currentDoctor) return;

        $.ajax({
            url: `/chat/messages/${currentDoctor}`,
            method: 'POST',
            data: { message: message },
            success: function(response) {
                $('#message-input').val('');
            }
        });
    }

    function displayNotification(type, data) {
        const notificationDiv = $(`
            <div class="notification ${type}-notification">
                <i class="fas fa-${type === 'appointment' ? 'calendar' : 
                          type === 'vaccination' ? 'syringe' : 
                          type === 'health-screening' ? 'stethoscope' : 'bell'}"></i>
                <div class="notification-content">
                    <h6>${type.replace('-', ' ').replace('health', 'health ').toUpperCase()}</h6>
                    <p>${data.description || data.status || data.name || data.title}</p>
                </div>
                <button class="btn btn-sm btn-primary" onclick="view${type.charAt(0).toUpperCase() + type.slice(1)}(${data.id || ''})">
                    View Details
                </button>
            </div>
        `);

        $(`#${type}-notifications`).prepend(notificationDiv);
        setTimeout(() => notificationDiv.fadeOut(5000, () => notificationDiv.remove()), 5000);
    }

    function viewAppointment(id) {
        window.location.href = `/appointments/${id}`;
    }

    function viewVaccination(id) {
        window.location.href = `/vaccinations/${id}`;
    }

    function viewHealthScreening(id) {
        window.location.href = `/health-screenings/${id}`;
    }
</script>
@endsection