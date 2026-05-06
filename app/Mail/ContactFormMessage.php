<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactFormMessage extends Mailable
{
    /**
     * @param array{name: string, email: string, phone?: string|null, message: string} $data
     */
    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->data['email'], $this->data['name'])],
            subject: 'New contact form message from '.$this->data['name'],
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.contact-message',
        );
    }
}
