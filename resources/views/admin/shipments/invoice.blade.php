@extends('layouts.invoice')

@section('title', 'Shipment ' . $shipment->tracking_number)

@section('content')
    @php
        $meta = $shipment->meta ?? [];
        $currency = $meta['currency'] ?? 'USD';
        $symbol = $currency === 'USD' ? '$' : ($currency === 'EUR' ? '€' : $currency . ' ');
    @endphp

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-8 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ $company['logo'] }}" alt="{{ $company['name'] }}" class="w-16 h-16 object-contain rounded bg-white border border-slate-100">
                    <div>
                        <h1 class="text-2xl font-bold text-navy">{{ $company['name'] }}</h1>
                        <p class="text-sm text-slate-500">{{ $company['address'] }}</p>
                        <p class="text-sm text-slate-500">{{ $company['phone'] }} &bull; {{ $company['email'] }}</p>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <h2 class="text-xl font-bold uppercase tracking-wide text-navy">Invoice</h2>
                    <p class="text-sm text-slate-600"><strong>Tracking #:</strong> {{ $shipment->tracking_number }}</p>
                    <p class="text-sm text-slate-600"><strong>Date:</strong> {{ now()->format('M d, Y') }}</p>
                    <p class="text-sm text-slate-600"><strong>Status:</strong> {{ $shipment->status }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 grid md:grid-cols-2 gap-8 border-b border-slate-200">
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Bill From</h3>
                <p class="font-semibold text-navy">{{ $company['name'] }}</p>
                <p class="text-sm text-slate-700">{{ $company['address'] }}</p>
                <p class="text-sm text-slate-700">{{ $company['phone'] }}</p>
                <p class="text-sm text-slate-700">{{ $company['email'] }}</p>
            </div>
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Bill To (Sender)</h3>
                <p class="font-semibold text-navy">{{ $shipment->sender_name }}</p>
                <p class="text-sm text-slate-700">{{ $shipment->sender_address ?? 'N/A' }}</p>
                <p class="text-sm text-slate-700">{{ $shipment->sender_phone ?? 'N/A' }}</p>
                <p class="text-sm text-slate-700">{{ $shipment->sender_email ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="p-8 border-b border-slate-200">
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-4">Recipient</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <p class="font-semibold text-navy">{{ $shipment->recipient_name }}</p>
                    <p class="text-sm text-slate-700">{{ $shipment->recipient_address ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-700">{{ $shipment->recipient_phone ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-700">{{ $shipment->recipient_email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-700"><strong>Origin:</strong> {{ $shipment->origin ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-700"><strong>Destination:</strong> {{ $shipment->destination ?? 'N/A' }}</p>
                    <p class="text-sm text-slate-700"><strong>Service:</strong> {{ $shipment->service }}</p>
                    <p class="text-sm text-slate-700"><strong>Payment Status:</strong> {{ $shipment->payment_status }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 border-b border-slate-200">
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-4">Shipment Details</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-slate-200 rounded">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Description</th>
                            <th class="px-4 py-3 text-left font-semibold">Quantity</th>
                            <th class="px-4 py-3 text-left font-semibold">Weight / Dimensions</th>
                            <th class="px-4 py-3 text-right font-semibold">Package Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">{{ $meta['product'] ?? 'General cargo' }}</td>
                            <td class="px-4 py-3">{{ $meta['quantity'] ?? 1 }}</td>
                            <td class="px-4 py-3">{{ $shipment->weight ?? '-' }} kg &bull; {{ $shipment->dimensions ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">{{ $meta['package_type'] ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-8 border-b border-slate-200">
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-4">Cost Summary</h3>
            <div class="max-w-md ml-auto">
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-600">Declared Value</span>
                    <span class="font-medium">{{ $symbol . number_format($shipment->declared_value ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-600">Shipping Cost</span>
                    <span class="font-medium">{{ $symbol . number_format($shipment->shipping_cost ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-600">Tax</span>
                    <span class="font-medium">{{ $symbol . number_format($shipment->tax ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between py-3 text-lg font-bold text-navy">
                    <span>Total</span>
                    <span>{{ $symbol . number_format($shipment->total_cost ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="p-8 bg-slate-50">
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-4">Tracking History</h3>
            <ul class="border-l-2 border-blue-200 pl-4 space-y-3 text-sm">
                @forelse ($shipment->events as $event)
                    <li>
                        <div class="text-slate-500">{{ $event->occurred_at?->format('M d, Y H:i') ?? 'N/A' }}</div>
                        <div class="font-semibold text-navy">{{ $event->status }}</div>
                        <div class="text-slate-700">{{ $event->description }} @if($event->location)<span class="text-slate-500">- {{ $event->location }}</span>@endif</div>
                    </li>
                @empty
                    <li class="text-slate-500">No events recorded.</li>
                @endforelse
            </ul>
        </div>

        <div class="p-6 text-center text-sm text-slate-500 border-t border-slate-200">
            Thank you for shipping with {{ $company['name'] }}. If you have questions, contact us at {{ $company['email'] }} or {{ $company['phone'] }}.
        </div>
    </div>
@endsection
