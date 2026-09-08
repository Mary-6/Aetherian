@extends('layouts.admin')

@section('title', 'Shipment Details')

@section('content')
    @php $meta = $shipment->meta ?? []; @endphp
    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-bold">{{ $shipment->tracking_number }}</h2>
                <div class="mt-2"><span class="px-3 py-1 rounded bg-blue-100 text-blue-800 text-sm font-bold">{{ $shipment->status }}</span></div>
            </div>
            <div class="space-x-2">
                <a href="{{ route('admin.shipments.edit', $shipment) }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800 inline-block">Edit</a>
                <a href="{{ route('admin.shipments.invoice', $shipment) }}" target="_blank" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600 inline-block">Invoice</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 text-sm">
            <div>
                <h3 class="font-bold mb-2 text-navy">Shipper Information</h3>
                <p><strong>Name:</strong> {{ $shipment->sender_name }}</p>
                <p><strong>Email:</strong> {{ $shipment->sender_email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $shipment->sender_phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $shipment->sender_address ?? 'N/A' }}</p>
                <p><strong>City:</strong> {{ $shipment->sender_city ?? 'N/A' }}</p>
                <p><strong>Country:</strong> {{ $shipment->sender_country ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="font-bold mb-2 text-navy">Receiver Information</h3>
                <p><strong>Name:</strong> {{ $shipment->recipient_name }}</p>
                <p><strong>Email:</strong> {{ $shipment->recipient_email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $shipment->recipient_phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $shipment->recipient_address ?? 'N/A' }}</p>
                <p><strong>City:</strong> {{ $shipment->recipient_city ?? 'N/A' }}</p>
                <p><strong>Country:</strong> {{ $shipment->recipient_country ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-6 text-sm space-y-1">
            <h3 class="font-bold mb-2 text-navy">Shipment Information</h3>
            <p><strong>Origin:</strong> {{ $shipment->origin ?: collect([$shipment->sender_city, $shipment->sender_country])->filter()->implode(', ') ?: 'N/A' }} &rarr; <strong>Destination:</strong> {{ $shipment->destination ?: collect([$shipment->recipient_city, $shipment->recipient_country])->filter()->implode(', ') ?: 'N/A' }}</p>
            <p><strong>Carrier:</strong> {{ $shipment->carrier ?? config('app.name') }}</p>
            <p><strong>Type of Shipment:</strong> {{ $meta['shipment_type'] ?? 'N/A' }}</p>
            <p><strong>Service:</strong> {{ $shipment->service ? ucwords(str_replace(['_', '-'], ' ', $shipment->service)) : 'N/A' }}</p>
            <p><strong>Status:</strong> {{ $shipment->status ? ucwords(str_replace(['_', '-'], ' ', $shipment->status)) : 'N/A' }}</p>
            <p><strong>Package:</strong> {{ $meta['package_type'] ?? 'N/A' }} | <strong>Product:</strong> {{ $meta['product'] ?? 'N/A' }} | <strong>Qty:</strong> {{ $meta['quantity'] ?? '-' }}</p>
            <p><strong>Piece Type:</strong> {{ $meta['piece_type'] ?? 'N/A' }} | <strong>Weight:</strong> {{ $shipment->weight ?? 'N/A' }} kg</p>
            <p><strong>Dimensions:</strong> {{ collect([$meta['length_cm'], $meta['width_cm'], $meta['height_cm']])->filter()->implode(' x ') ?: 'N/A' }} cm</p>
            <p><strong>Carrier Reference No.:</strong> {{ $meta['carrier_reference'] ?? 'N/A' }}</p>
            <p><strong>Payment Mode:</strong> {{ $meta['payment_mode'] ?? 'N/A' }}</p>
            <p><strong>Total Freight:</strong> {{ $meta['total_freight'] ?? 'N/A' }}</p>
            <p><strong>Declared Value:</strong> {{ $shipment->declared_value ?? 'N/A' }} | <strong>Amount Due:</strong> {{ $shipment->payment_amount ?? 'N/A' }} <strong>{{ $shipment->currency ?? 'USD' }}</strong></p>
            <p><strong>Pick-up Date:</strong> {{ $shipment->pickup_date?->format('M d, Y') ?? 'N/A' }} | <strong>Pick-up Time:</strong> {{ $meta['pickup_time'] ?? 'N/A' }}</p>
            <p><strong>Departure Time:</strong> {{ $shipment->departure_time?->format('M d, Y H:i') ?? 'N/A' }}</p>
            <p><strong>Expected Delivery:</strong> {{ $shipment->estimated_delivery_at?->format('M d, Y') ?? 'N/A' }}</p>
            <p><strong>Comments:</strong> {{ $meta['comments'] ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow mb-6">
        <h3 class="font-bold mb-4">Live Map</h3>
        <div id="shipment-map" class="w-full h-[400px] rounded border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-500">Loading map...</div>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-bold mb-4">Tracking History</h3>
        <ul class="border-l-2 border-blue-200 pl-4 space-y-4">
            @forelse ($shipment->events as $event)
                <li>
                    <div class="text-sm text-slate-500">{{ $event->occurred_at->format('M d, Y H:i') }}</div>
                    <div class="font-semibold">{{ $event->status }}</div>
                    <div class="text-sm">{{ $event->description }} @if($event->location)<span class="text-slate-500">- {{ $event->location }}</span>@endif</div>
                </li>
            @empty
                <li>No events yet.</li>
            @endforelse
        </ul>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            (function () {
                async function geocode(query) {
                    const attempts = [query, query.split(',')[0].trim(), query.replace(/,?\s*(USA?|United States|United Kingdom|UK|England|Scotland|Wales)\s*$/i, '').trim()];
                    for (const q of attempts) {
                        if (!q) continue;
                        try {
                            const res = await fetch('https://geocoding-api.open-meteo.com/v1/search?name=' + encodeURIComponent(q) + '&count=1');
                            const data = await res.json();
                            if (data && data.results && data.results[0]) {
                                return { lat: data.results[0].latitude, lng: data.results[0].longitude };
                            }
                        } catch (e) { console.error('Geocode error', e); }
                    }
                    return null;
                }

                function interpolate(start, end, fraction) {
                    return { lat: start.lat + (end.lat - start.lat) * fraction, lng: start.lng + (end.lng - start.lng) * fraction };
                }

                const origin = {!! json_encode($shipment->origin ?: collect([$shipment->sender_city, $shipment->sender_country])->filter()->implode(', ') ?: '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                const destination = {!! json_encode($shipment->destination ?: collect([$shipment->recipient_city, $shipment->recipient_country])->filter()->implode(', ') ?: '', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                const status = {!! json_encode($shipment->status, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                const currentLat = {!! json_encode($meta['current_lat'] ?? null, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                const currentLng = {!! json_encode($meta['current_lng'] ?? null, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

                async function initMap() {
                    const originCoords = await geocode(origin);
                    const destCoords = await geocode(destination);
                    const container = document.getElementById('shipment-map');
                    if (!originCoords || !destCoords) {
                        container.innerHTML = 'Could not load map coordinates.';
                        return;
                    }

                    let currentCoords = currentLat && currentLng ? { lat: currentLat, lng: currentLng } : null;
                    if (!currentCoords) {
                        if (status === 'DELIVERED') currentCoords = destCoords;
                        else if (status === 'PENDING' || status === 'ON_HOLD') currentCoords = originCoords;
                        else currentCoords = interpolate(originCoords, destCoords, 0.5);
                    }

                    const map = L.map(container).setView([currentCoords.lat, currentCoords.lng], 5);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

                    L.marker([originCoords.lat, originCoords.lng]).addTo(map).bindPopup('Origin: ' + origin);
                    L.marker([destCoords.lat, destCoords.lng]).addTo(map).bindPopup('Destination: ' + destination);
                    L.marker([currentCoords.lat, currentCoords.lng]).addTo(map).bindPopup('Current location');

                    L.polyline([[originCoords.lat, originCoords.lng], [currentCoords.lat, currentCoords.lng], [destCoords.lat, destCoords.lng]], { color: '#0D7377', weight: 4, opacity: 0.8, dashArray: '8,8' }).addTo(map);
                }

                initMap();
            })();
        </script>
    @endpush
@endsection
