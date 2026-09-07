@extends('layouts.public')

@section('title', 'Track Shipment - Aetherian Cargo')

@section('content')
    @php
        $number = request('number') ?: ($shipment?->tracking_number ?? '');
    @endphp

    {{-- Page banner --}}
    <section class="relative overflow-hidden rounded-[20px] mx-4 sm:mx-6 lg:mx-8 mt-4">
        <div class="absolute inset-0">
            <img src="{{ asset('images/truck.jpg') }}" alt="Logistics banner" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-navy/80"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
            <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-3">Track Shipment</h1>
            <nav class="text-sm text-slate-300">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">Track Shipment</span>
            </nav>
        </div>
    </section>

    {{-- Track form --}}
    <section class="py-16 lg:py-24 bg-[#F5F5F5]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <p class="text-brand-600 font-semibold uppercase tracking-widest text-sm mb-3">Track & Trace</p>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mb-4">Track your shipment</h2>
                <p class="text-slate-600">Enter your tracking id below to get live updates on your cargo.</p>
            </div>

            <form action="{{ route('track') }}" method="GET" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-3 bg-white p-2 rounded-2xl shadow-lg border border-slate-200 print:hidden">
                <div class="flex-1 relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                    <input type="text" name="number" placeholder="Enter your tracking id (e.g. AC...)" value="{{ $number }}"
                        class="w-full pl-12 pr-4 py-4 rounded-xl bg-slate-50 text-slate-900 placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500" required>
                </div>
                <button type="submit" class="px-8 py-4 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition-colors whitespace-nowrap">Track</button>
            </form>

            @if ($number || $shipment)
                <div class="mt-10">
                    @if ($shipment)
                        @php
                            $meta = $shipment->meta ?? [];
                            $currency = $meta['currency'] ?? 'USD';
                            $symbol = ['USD' => '$', 'EUR' => '€', 'GBP' => '£'][$currency] ?? $currency.' ';
                            $origin = $shipment->origin ?: collect([$shipment->sender_country])->filter()->first() ?: 'N/A';
                            $destination = $shipment->destination ?: collect([$shipment->recipient_country])->filter()->first() ?: 'N/A';
                            $packageType = $meta['package_type'] ?? $meta['product'] ?? '-';
                            $product = $meta['product'] ?? '-';
                            $carrier = $meta['carrier_reference'] ?? 'Aetherian Cargo';
                            $shipmentType = $meta['shipment_type'] ?? ($shipment->service ? ucwords(str_replace(['_', '-'], ' ', $shipment->service)) : '-');
                            $weight = $shipment->weight ? $shipment->weight . ' kg' : '-';
                            $paymentMode = $meta['payment_mode'] ?? '-';
                            $totalFreight = $meta['total_freight'] ? $symbol . number_format($meta['total_freight'], 2) : ($shipment->total_cost ? $symbol . number_format($shipment->total_cost, 2) : '-');
                            $totalCost = $shipment->total_cost ? $symbol . number_format($shipment->total_cost, 2) : '-';
                        @endphp

                        <div class="bg-white rounded-none sm:rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-10 print:shadow-none print:border-0">
                            <div class="flex justify-end print:hidden mb-4">
                                <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent-500 text-white rounded-lg hover:bg-accent-600 transition-colors">
                                    <i data-lucide="printer" class="w-4 h-4"></i> Print Shipment
                                </button>
                            </div>

                            <div class="flex flex-col items-center border-b border-slate-200 pb-6 mb-6">
                                <img src="{{ url('logo.png') }}" alt="Aetherian Cargo" class="w-32 h-32 object-contain mb-4">
                                <h2 class="text-2xl font-bold text-brand-600 text-center">Aetherian Cargo</h2>
                                <p class="text-sm text-slate-500 tracking-wider text-center uppercase">International Freight</p>
                                <svg id="barcode" class="mt-6 w-full max-w-xs"></svg>
                                <p class="font-mono text-lg font-semibold text-slate-900 mt-1">{{ $shipment->tracking_number }}</p>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between gap-6 mb-8">
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-slate-700 border-b border-slate-400 pb-1 mb-4">Shipper Information</h4>
                                    <div class="space-y-1 text-slate-600">
                                        <p class="font-semibold text-slate-900">{{ $shipment->sender_name }}</p>
                                        <p>{{ $shipment->sender_address ?? 'N/A' }}</p>
                                        <p>{{ collect([$shipment->origin, $shipment->sender_country])->filter()->implode(', ') ?: 'N/A' }}</p>
                                        @if ($shipment->sender_phone)<p class="flex items-center gap-2"><i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i> {{ $shipment->sender_phone }}</p>@endif
                                        @if ($shipment->sender_email)<p class="flex items-center gap-2"><i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i> {{ $shipment->sender_email }}</p>@endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-slate-700 border-b border-slate-400 pb-1 mb-4">Receiver Information</h4>
                                    <div class="space-y-1 text-slate-600">
                                        <p class="font-semibold text-slate-900">{{ $shipment->recipient_name }}</p>
                                        <p>{{ $shipment->recipient_address ?? 'N/A' }}</p>
                                        <p>{{ collect([$shipment->destination, $shipment->recipient_country])->filter()->implode(', ') ?: 'N/A' }}</p>
                                        @if ($shipment->recipient_phone)<p class="flex items-center gap-2"><i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i> {{ $shipment->recipient_phone }}</p>@endif
                                        @if ($shipment->recipient_email)<p class="flex items-center gap-2"><i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i> {{ $shipment->recipient_email }}</p>@endif
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-400 text-white text-center py-3 uppercase tracking-widest text-sm font-semibold my-6">
                                Shipment Status: {{ ucwords(str_replace(['_', '-'], ' ', $shipment->status)) }}
                            </div>

                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-slate-700 border-b border-slate-400 pb-1 mb-4">Shipment Information</h4>
                                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                                    <div><p class="font-bold text-slate-700">Origin</p><p class="text-slate-600">{{ $origin }}</p></div>
                                    <div><p class="font-bold text-slate-700">Package</p><p class="text-slate-600">{{ $packageType }}</p></div>
                                    <div><p class="font-bold text-slate-700">Status</p><p class="text-slate-600">{{ ucwords(str_replace(['_', '-'], ' ', $shipment->status)) }}</p></div>
                                    <div><p class="font-bold text-slate-700">Destination</p><p class="text-slate-600">{{ $destination }}</p></div>
                                    <div><p class="font-bold text-slate-700">Carrier</p><p class="text-slate-600">{{ $carrier }}</p></div>
                                    <div><p class="font-bold text-slate-700">Type of Shipment</p><p class="text-slate-600">{{ $shipmentType }}</p></div>
                                    <div><p class="font-bold text-slate-700">Weight</p><p class="text-slate-600">{{ $weight }}</p></div>
                                    <div><p class="font-bold text-slate-700">Shipment Mode</p><p class="text-slate-600">{{ $shipment->service ? ucwords(str_replace(['_', '-'], ' ', $shipment->service)) : '-' }}</p></div>
                                    <div><p class="font-bold text-slate-700">Carrier Reference No.</p><p class="text-slate-600">{{ $meta['carrier_reference'] ?? $shipment->tracking_number }}</p></div>
                                    <div><p class="font-bold text-slate-700">Product</p><p class="text-slate-600">{{ $product }}</p></div>
                                    <div><p class="font-bold text-slate-700">Qty</p><p class="text-slate-600">{{ $meta['quantity'] ?? '-' }}</p></div>
                                    <div><p class="font-bold text-slate-700">Payment Mode</p><p class="text-slate-600">{{ $paymentMode }}</p></div>
                                    <div><p class="font-bold text-slate-700">Payment Status</p><p class="text-slate-600">{{ $shipment->payment_status ? ucwords($shipment->payment_status) : '-' }}</p></div>
                                    <div><p class="font-bold text-slate-700">Currency</p><p class="text-slate-600">{{ $currency }}</p></div>
                                    <div><p class="font-bold text-slate-700">Total Freight</p><p class="text-slate-600">{{ $totalFreight }}</p></div>
                                    <div><p class="font-bold text-slate-700">Total Cost</p><p class="text-slate-600">{{ $totalCost }}</p></div>
                                    @if (!empty($meta['estimated_delivery']))
                                        <div><p class="font-bold text-slate-700">Expected Delivery Date</p><p class="text-slate-600">{{ $meta['estimated_delivery'] }}</p></div>
                                    @endif
                                    @if (!empty($meta['departure_time']))
                                        <div><p class="font-bold text-slate-700">Departure Time</p><p class="text-slate-600">{{ $meta['departure_time'] }}</p></div>
                                    @endif
                                    @if (!empty($meta['pickup_date']))
                                        <div><p class="font-bold text-slate-700">Pick-up Date</p><p class="text-slate-600">{{ $meta['pickup_date'] }}</p></div>
                                    @endif
                                    @if (!empty($meta['pickup_time']))
                                        <div><p class="font-bold text-slate-700">Pick-up Time</p><p class="text-slate-600">{{ $meta['pickup_time'] }}</p></div>
                                    @endif
                                    @if ($shipment->shipped_at)
                                        <div><p class="font-bold text-slate-700">Shipped At</p><p class="text-slate-600">{{ $shipment->shipped_at->format('M d, Y H:i') }}</p></div>
                                    @endif
                                    @if ($shipment->delivered_at)
                                        <div><p class="font-bold text-slate-700">Delivered At</p><p class="text-slate-600">{{ $shipment->delivered_at->format('M d, Y H:i') }}</p></div>
                                    @endif
                                    @if (!empty($meta['comments']))
                                        <div class="sm:col-span-2"><p class="font-bold text-slate-700">Comments</p><p class="text-slate-600">{{ $meta['comments'] }}</p></div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-slate-700 border-b border-slate-400 pb-1 mb-4">Packages</h4>
                                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                    <table class="w-full text-sm text-left">
                                        <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                                            <tr>
                                                <th class="p-3 font-semibold">Qty</th>
                                                <th class="p-3 font-semibold">Piece Type</th>
                                                <th class="p-3 font-semibold">Description</th>
                                                <th class="p-3 font-semibold">Length</th>
                                                <th class="p-3 font-semibold">Width</th>
                                                <th class="p-3 font-semibold">Height</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b border-slate-100">
                                                <td class="p-3">{{ $meta['quantity'] ?? '-' }}</td>
                                                <td class="p-3">{{ $meta['piece_type'] ?? '-' }}</td>
                                                <td class="p-3">{{ $product }}</td>
                                                <td class="p-3">{{ !empty($meta['length_cm']) ? $meta['length_cm'] . ' cm' : '-' }}</td>
                                                <td class="p-3">{{ !empty($meta['width_cm']) ? $meta['width_cm'] . ' cm' : '-' }}</td>
                                                <td class="p-3">{{ !empty($meta['height_cm']) ? $meta['height_cm'] . ' cm' : '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-slate-700 border-b border-slate-400 pb-1 mb-4">Live Map</h4>
                                <div id="shipment-map" class="w-full h-[400px] rounded-2xl overflow-hidden shadow-lg border border-slate-200 z-0 bg-slate-50 flex items-center justify-center text-slate-500">
                                    Loading map...
                                </div>
                            </div>

                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-slate-700 border-b border-slate-400 pb-1 mb-4">Shipment History</h4>
                                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                    <table class="w-full text-sm text-left">
                                        <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                                            <tr>
                                                <th class="p-3 font-semibold">Date</th>
                                                <th class="p-3 font-semibold">Time</th>
                                                <th class="p-3 font-semibold">Location</th>
                                                <th class="p-3 font-semibold">Status</th>
                                                <th class="p-3 font-semibold">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($shipment->events as $event)
                                                <tr class="border-b border-slate-100">
                                                    <td class="p-3 whitespace-nowrap">{{ $event->occurred_at->format('M d, Y') }}</td>
                                                    <td class="p-3 whitespace-nowrap">{{ $event->occurred_at->format('H:i') }}</td>
                                                    <td class="p-3">{{ $event->location ?: '-' }}</td>
                                                    <td class="p-3">{{ ucwords(str_replace(['_', '-'], ' ', $event->status)) }}</td>
                                                    <td class="p-3">{{ $event->description ?: '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="p-3 text-slate-500">No events yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @push('styles')
                            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
                        @endpush

                        @push('scripts')
                            <script src="https://unpkg.com/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
                            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                            <script>
                                (function () {
                                    JsBarcode('#barcode', {!! json_encode($shipment->tracking_number, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}, { format: 'CODE128', lineColor: '#0f172a', width: 2, height: 60, displayValue: false });

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

                                    const origin = {!! json_encode($origin, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                                    const destination = {!! json_encode($destination, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                                    const status = {!! json_encode($shipment->status, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                                    const currentLat = {!! json_encode($meta['current_lat'] ?? null, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};
                                    const currentLng = {!! json_encode($meta['current_lng'] ?? null, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

                                    async function initMap() {
                                        const originCoords = await geocode(origin);
                                        const destCoords = await geocode(destination);
                                        const container = document.getElementById('shipment-map');
                                        if (!originCoords || !destCoords) {
                                            container.innerHTML = 'Could not load map coordinates for this shipment.';
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
                    @else
                        <div class="mt-8 p-6 bg-red-50 text-red-700 rounded-2xl border border-red-100 text-center print:hidden">
                            No shipment found for <strong>{{ $number }}</strong>.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
