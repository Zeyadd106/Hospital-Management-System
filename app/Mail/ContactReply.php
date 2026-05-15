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

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\ContactMessage  $message
     * @return void
     */
    public function __construct(ContactMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->message->reply_subject ?? 'Reply to Your Message')
                    ->view('emails.contact_reply')
                    ->with([
                        'reply' => $this->message->reply,
                        'name' => $this->message->name,
                        'original_message' => $this->message->message,
                        'original_subject' => $this->message->subject
                    ]);
    }
}