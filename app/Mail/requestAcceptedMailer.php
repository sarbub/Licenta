<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class requestAcceptedMailer extends Mailable
{
    use Queueable, SerializesModels;
    public $request;
    public $response;

    /**
     * Create a new message instance.
     */
    public function __construct($request, $response)
    {
        //
        $this->request = $request; 
        $this->response = $response; 
    }

    /**
     * Get the message envelope.
    */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request Accepted Mailer',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.requestAcceptedEmailView',
            with: ['request' => $this->request,
        'response' => $this->response],
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
