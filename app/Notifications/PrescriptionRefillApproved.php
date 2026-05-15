<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PrescriptionRefill;

class PrescriptionRefillApproved extends Notification
{
    use Queueable;

    protected $prescriptionRefill;

    /**
     * Create a new notification instance.
     *
     * @param PrescriptionRefill $prescriptionRefill
     * @return void
     */
    public function __construct(PrescriptionRefill $prescriptionRefill)
    {
        $this->prescriptionRefill = $prescriptionRefill;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $prescription = $this->prescriptionRefill->prescription;
        $doctor = $prescription->doctor;

        return (new MailMessage)
                    ->subject('Prescription Refill Approved')
                    ->line('Your prescription refill request has been approved by Dr. ' . $doctor->name . '.')
                    ->line('Prescription: ' . $prescription->name)
                    ->action('View Details', url('/prescriptions'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'prescription_refill_id' => $this->prescriptionRefill->id,
            'prescription_id' => $this->prescriptionRefill->prescription_id,
            'doctor_id' => $this->prescriptionRefill->prescription->doctor_id,
            'type' => 'prescription_refill_approved'
        ];
    }
}
