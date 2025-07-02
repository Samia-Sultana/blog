<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ViserXMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailContent;
    public $mailSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
                                $mailContent,
                                $mailSubject = 'Hossain Litigation and Law Admistration Mail',
                                )
    {
        $this->mailContent = $mailContent;
        $this->mailSubject = $mailSubject;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->mailSubject,
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
            view: 'viserxMail',
            with: ['content' => $this->mailContent, 'mailSubject' => $this->mailSubject]
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
