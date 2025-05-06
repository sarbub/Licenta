<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailFile extends Mailable
{

    public $code;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($code)
    {
        $this->code = $code['code'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cod de validare pentru cererea ta',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Pass the code to the Blade view
        return new Content(
            view: 'emails.welcome', // The Blade view that will be rendered
            with: ['code' => $this->code], // Share the code variable with the view
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
