<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class requestsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nume;
    public $prenume;
    public $varsta;
    public $universitate;
    public $judet;
    public $numarFrati;
    public $venitFamilie;

    /**
     * Create a new message instance.
     */
    public function __construct($nume, $prenume, $varsta, $universitate, $judet, $numarFrati, $venitFamilie)
{
    $this->nume = $nume;
    $this->prenume = $prenume;
    $this->varsta = $varsta;
    $this->universitate = $universitate;
    $this->judet = $judet;
    $this->numarFrati = $numarFrati;
    $this->venitFamilie = $venitFamilie;
}


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cerere de cazare cămin',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sendRequestEmailView', // this is the blade view that will be rendered
            with:[
                'nume' => $this->nume,
                'prenume' => $this->prenume,
                'varsta' => $this->varsta,
                'universitate' => $this->universitate,
                'judet' => $this->judet,
                'numarFrati' => $this->numarFrati,
                'venitFamilie' => $this->venitFamilie
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
