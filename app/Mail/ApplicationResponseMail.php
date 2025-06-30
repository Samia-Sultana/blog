<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationResponseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $name;
    public $position;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $position = null)
    {
        $this->name = $name;
        $this->position = $position;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: "Thanks for Applying at VISER X for {$this->position}.",
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.applicationResponseMail',
            with: ['name' => $this->name, 'position' => $this->position],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
