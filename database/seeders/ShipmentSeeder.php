<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        $shipment = Shipment::firstOrCreate(
            ['tracking_number' => 'AC094D5704'],
            [
                'created_by' => 1,
                'sender_name' => 'Demo Sender',
                'sender_email' => 'sender@example.com',
                'sender_phone' => '+1 555 0101',
                'sender_address' => '123 Sender Street',
                'sender_city' => 'New York',
                'sender_country' => 'United States',
                'recipient_name' => 'Demo Recipient',
                'recipient_email' => 'recipient@example.com',
                'recipient_phone' => '+1 555 0199',
                'recipient_address' => '456 Recipient Ave',
                'recipient_city' => 'Berlin',
                'recipient_country' => 'Germany',
                'origin' => 'New York, USA',
                'destination' => 'Berlin, Germany',
                'carrier' => config('app.name'),
                'weight' => 12.5,
                'service' => 'EXPRESS',
                'declared_value' => 500.00,
                'payment_amount' => 135.00,
                'currency' => 'USD',
                'pickup_date' => now()->subDays(3)->format('Y-m-d'),
                'departure_time' => now()->subDays(2),
                'estimated_delivery_at' => now()->addDays(3)->format('Y-m-d'),
                'status' => 'IN_TRANSIT',
                'notes' => 'Demo shipment with live map.',
                'meta' => [
                    'quantity' => 2,
                    'piece_type' => 'Carton',
                    'package_type' => 'Box',
                    'product' => 'Electronics',
                    'carrier_reference' => 'CARGO-8831',
                    'shipment_type' => 'International',
                    'payment_mode' => 'Card',
                    'total_freight' => '5hrs drive',
                    'current_lat' => 50.1109,
                    'current_lng' => 8.6821,
                    'comments' => 'Demo shipment with live map.',
                ],
                'shipped_at' => now()->subDays(2),
            ]
        );

        if ($shipment->wasRecentlyCreated) {
            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status' => 'PENDING',
                'description' => 'Shipment information received',
                'location' => 'New York, USA',
                'occurred_at' => now()->subDays(3),
            ]);

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status' => 'IN_TRANSIT',
                'description' => 'Shipment departed origin facility',
                'location' => 'New York, USA',
                'occurred_at' => now()->subDays(2),
            ]);

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status' => 'IN_TRANSIT',
                'description' => 'Arrived at transit facility',
                'location' => 'Frankfurt, Germany',
                'occurred_at' => now()->subHours(8),
            ]);
        }
    }
}
