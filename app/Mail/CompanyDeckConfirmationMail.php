<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyDeckConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email;
    public $pdfUrl;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $pdfUrl = null, $name)
    {
        $this->email = $email;
        $this->pdfUrl = $pdfUrl;
        $this->name = $name;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'VISER X Company Deck | Empowering Digital Presence',
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('email.companyDeckMail')
                    ->subject('VISER X Company Deck | Empowering Digital Presence');
    }
}
