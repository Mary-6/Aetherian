@extends('layouts.admin')

@section('title', 'Shipment Details')

@section('content')
    @php $meta = $shipment->meta ?? []; @endphp
    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="flex justify-between items-start">
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
                <h3 class="font-bold mb-2">Sender</h3>
                <p><strong>Name:</strong> {{ $shipment->sender_name }}</p>
                <p><strong>Email:</strong> {{ $shipment->sender_email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $shipment->sender_phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $shipment->sender_address ?? 'N/A' }}</p>
                <p><strong>Country:</strong> {{ $shipment->sender_country ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="font-bold mb-2">Recipient</h3>
                <p><strong>Name:</strong> {{ $shipment->recipient_name }}</p>
                <p><strong>Email:</strong> {{ $shipment->recipient_email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $shipment->recipient_phone ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $shipment->recipient_address ?? 'N/A' }}</p>
                <p><strong>Country:</strong> {{ $shipment->recipient_country ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-6 text-sm">
            <h3 class="font-bold mb-2">Shipment Info</h3>
            <p><strong>Origin:</strong> {{ $shipment->origin ?? 'N/A' }} &rarr; <strong>Destination:</strong> {{ $shipment->destination ?? 'N/A' }}</p>
            <p><strong>Service:</strong> {{ $shipment->service }} | <strong>Weight:</strong> {{ $shipment->weight ?? 'N/A' }} | <strong>Dimensions:</strong> {{ $shipment->dimensions ?? 'N/A' }}</p>
            <p><strong>Declared Value:</strong> {{ $shipment->declared_value ?? 'N/A' }} | <strong>Shipping Cost:</strong> {{ $shipment->shipping_cost ?? 'N/A' }} | <strong>Tax:</strong> {{ $shipment->tax ?? 'N/A' }} | <strong>Total:</strong> {{ $shipment->total_cost ?? 'N/A' }}</p>
            <p><strong>Payment Status:</strong> {{ $shipment->payment_status }}</p>
            <p><strong>Branch:</strong> {{ $shipment->branch?->name ?? 'N/A' }} | <strong>Driver:</strong> {{ $shipment->driver?->name ?? 'N/A' }}</p>
            <p><strong>Qty:</strong> {{ $meta['quantity'] ?? 'N/A' }} | <strong>Piece Type:</strong> {{ $meta['piece_type'] ?? 'N/A' }} | <strong>Package Type:</strong> {{ $meta['package_type'] ?? 'N/A' }} | <strong>Product:</strong> {{ $meta['product'] ?? 'N/A' }}</p>
            <p><strong>Carrier Ref:</strong> {{ $meta['carrier_reference'] ?? 'N/A' }} | <strong>Payment Mode:</strong> {{ $meta['payment_mode'] ?? 'N/A' }} | <strong>Total Freight:</strong> {{ $meta['total_freight'] ?? 'N/A' }}</p>
            <p><strong>Current Coordinates:</strong> {{ $meta['current_lat'] ?? 'N/A' }}, {{ $meta['current_lng'] ?? 'N/A' }}</p>
            <p><strong>Comments:</strong> {{ $meta['comments'] ?? 'N/A' }}</p>
            <p><strong>Notes:</strong> {{ $shipment->notes ?? 'N/A' }}</p>
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
                    try {
                        const res = await fetch('https://geocoding-api.open-meteo.com/v1/search?name=' + encodeURIComponent(query) + '&count=1');
                        const data = await res.json();
                        if (data && data.results && data.results[0]) {
                            return { lat: data.results[0].latitude, lng: data.results[0].longitude };
                        }
                    } catch (e) { console.error('Geocode error', e); }
                    return null;
                }

                function interpolate(start, end, fraction) {
                    return { lat: start.lat + (end.lat - start.lat) * fraction, lng: start.lng + (end.lng - start.lng) * fraction };
                }

                const origin = {!! json_encode($shipment->origin ?: ($shipment->sender_country ?: ''), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                const destination = {!! json_encode($shipment->destination ?: ($shipment->recipient_country ?: ''), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
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
