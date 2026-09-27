<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShipmentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Shipment $shipment;

    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Your shipment ' . $this->shipment->tracking_number . ' has been created',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.shipments.created',
            with: [
                'companyName' => Setting::get('company_name', config('app.name')),
                'companyEmail' => Setting::get('company_email', config('mail.from.address')),
                'companyPhone' => Setting::get('company_phone', '+1 (423) 277-8587'),
                'trackingUrl' => route('track', ['number' => $this->shipment->tracking_number]),
            ],
        );
    }
}
