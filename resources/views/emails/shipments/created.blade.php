<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Created</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; color: #333; margin: 0; }
        .container { max-width: 620px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #0f2a4a; color: #fff; padding: 20px 24px; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { padding: 24px; }
        .tracking { background: #f0f6ff; border: 1px dashed #0f2a4a; border-radius: 6px; padding: 16px; text-align: center; margin: 16px 0; }
        .tracking .number { font-size: 24px; font-weight: bold; letter-spacing: 2px; color: #0f2a4a; }
        .btn { display: inline-block; background: #0f2a4a; color: #fff !important; text-decoration: none; padding: 12px 22px; border-radius: 999px; font-weight: bold; margin-top: 10px; }
        h3 { margin: 22px 0 8px; font-size: 15px; color: #0f2a4a; border-bottom: 1px solid #eee; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 0; font-size: 14px; vertical-align: top; }
        td.label { color: #666; width: 40%; }
        .footer { padding: 16px 24px; font-size: 12px; color: #888; background: #fafafa; }
    </style>
</head>
<body>
@php
    $meta = $shipment->meta ?? [];
    $modes = ['AIR_FREIGHT' => 'Air Freight', 'SEA_FREIGHT' => 'Sea Freight', 'ROAD_FREIGHT' => 'Road Freight', 'EXPRESS' => 'Express', 'OVERNIGHT' => 'Overnight'];
    $rows = [
        'Status' => str_replace('_', ' ', $shipment->status),
        'Shipment Mode' => $modes[$shipment->service] ?? $shipment->service,
        'Type of Shipment' => $meta['shipment_type'] ?? null,
        'Carrier' => $shipment->carrier,
        'Carrier Reference No.' => $meta['carrier_reference'] ?? null,
        'Origin' => $shipment->origin,
        'Destination' => $shipment->destination,
        'Package' => $meta['package_type'] ?? null,
        'Product' => $meta['product'] ?? null,
        'Quantity' => $meta['quantity'] ?? null,
        'Weight (kg)' => $shipment->weight,
        'Declared Value' => $shipment->declared_value,
        'Payment Mode' => $meta['payment_mode'] ?? null,
        'Pick-up Date' => $shipment->pickup_date?->format('M d, Y'),
        'Estimated Arrival Time' => $meta['pickup_time'] ?? null,
        'Departure Time' => $shipment->departure_time?->format('M d, Y H:i'),
        'Expected Delivery Date' => $shipment->estimated_delivery_at?->format('M d, Y'),
        'Comments' => $meta['comments'] ?? null,
    ];
@endphp
<div class="container">
    <div class="header">
        <h1>{{ $companyName }}</h1>
        <p style="margin:6px 0 0;font-size:13px;">Your shipment has been created</p>
    </div>
    <div class="body">
        <p>Hi,</p>
        <p>Your shipment has been created with {{ $companyName }}. Use the tracking number below to follow its progress at any time.</p>

        <div class="tracking">
            <div style="font-size:12px;color:#666;">Tracking Number</div>
            <div class="number">{{ $shipment->tracking_number }}</div>
            <a class="btn" href="{{ $trackingUrl }}">Track Shipment</a>
        </div>

        <h3>Shipment Details</h3>
        <table>
            @foreach ($rows as $label => $value)
                @if ($value !== null && $value !== '')
                    <tr><td class="label">{{ $label }}</td><td>{{ $value }}</td></tr>
                @endif
            @endforeach
        </table>

        <h3>Shipper</h3>
        <table>
            <tr><td class="label">Name</td><td>{{ $shipment->sender_name }}</td></tr>
            @if ($shipment->sender_phone)<tr><td class="label">Phone</td><td>{{ $shipment->sender_phone }}</td></tr>@endif
            @if ($shipment->sender_email)<tr><td class="label">Email</td><td>{{ $shipment->sender_email }}</td></tr>@endif
            @if ($shipment->sender_address || $shipment->sender_city || $shipment->sender_country)
                <tr><td class="label">Address</td><td>{{ collect([$shipment->sender_address, $shipment->sender_city, $shipment->sender_country])->filter()->implode(', ') }}</td></tr>
            @endif
        </table>

        <h3>Receiver</h3>
        <table>
            <tr><td class="label">Name</td><td>{{ $shipment->recipient_name }}</td></tr>
            @if ($shipment->recipient_phone)<tr><td class="label">Phone</td><td>{{ $shipment->recipient_phone }}</td></tr>@endif
            @if ($shipment->recipient_email)<tr><td class="label">Email</td><td>{{ $shipment->recipient_email }}</td></tr>@endif
            @if ($shipment->recipient_address || $shipment->recipient_city || $shipment->recipient_country)
                <tr><td class="label">Address</td><td>{{ collect([$shipment->recipient_address, $shipment->recipient_city, $shipment->recipient_country])->filter()->implode(', ') }}</td></tr>
            @endif
        </table>

        <p style="margin-top:22px;">If you have any questions, contact us at {{ $companyEmail }} or {{ $companyPhone }}.</p>
        <p>Thank you for choosing {{ $companyName }}.</p>
    </div>
    <div class="footer">This is an automated message from {{ $companyName }}.</div>
</div>
</body>
</html>
