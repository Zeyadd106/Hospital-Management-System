<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ContactMessage;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct(ContactMessage $message)
    {
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject($this->message->reply_subject ?? 'Reply to Your Message')
                    ->view('emails.contact_reply')
                    ->with(['reply' => $this->message->reply]);
    }
}