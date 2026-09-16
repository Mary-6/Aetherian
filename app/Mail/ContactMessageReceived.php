<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived extends Mailable
{
    use Queueable, SerializesModels;

    public ContactMessage $contact;

    public function __construct(ContactMessage $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        $to = Setting::get('company_email', config('mail.from.address'));

        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            replyTo: [new Address($this->contact->email, $this->contact->name)],
            to: [new Address($to)],
            subject: 'New contact message: ' . ($this->contact->subject ?: 'No subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
