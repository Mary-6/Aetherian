@extends('layouts.invoice')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
    @php
        $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£'];
        $symbol = $symbols[$invoice->currency] ?? $invoice->currency . ' ';
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
                    <p class="text-sm text-slate-600"><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                    <p class="text-sm text-slate-600"><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                    @if ($invoice->due_date)
                        <p class="text-sm text-slate-600"><strong>Due:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                    @endif
                    <p class="text-sm text-slate-600"><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 border-b border-slate-200">
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Bill To</h3>
            <p class="font-semibold text-navy">{{ $invoice->client_name }}</p>
            @if ($invoice->client_address)<p class="text-sm text-slate-700">{{ $invoice->client_address }}</p>@endif
            @if ($invoice->client_phone)<p class="text-sm text-slate-700">{{ $invoice->client_phone }}</p>@endif
            @if ($invoice->client_email)<p class="text-sm text-slate-700">{{ $invoice->client_email }}</p>@endif
        </div>

        <div class="p-8 border-b border-slate-200">
            <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-4">Invoice Details</h3>
            <table class="w-full text-sm border border-slate-200 rounded">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Description</th>
                        <th class="px-4 py-3 text-right font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">{{ $invoice->description ?: 'Shipping services' }}</td>
                        <td class="px-4 py-3 text-right">{{ $symbol . number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end mt-4">
                <div class="max-w-xs w-full">
                    <div class="flex justify-between py-3 text-lg font-bold text-navy border-t border-slate-200">
                        <span>Total Due</span>
                        <span>{{ $symbol . number_format($invoice->amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if ($invoice->shipment)
            <div class="p-8 border-b border-slate-200">
                <h3 class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Linked Shipment</h3>
                <p class="text-sm text-slate-700"><strong>Tracking #:</strong> {{ $invoice->shipment->tracking_number }}</p>
                <p class="text-sm text-slate-700"><strong>Destination:</strong> {{ $invoice->shipment->destination }}</p>
            </div>
        @endif

        <div class="p-6 text-center text-sm text-slate-500 border-t border-slate-200">
            Thank you for your business. If you have questions, contact us at {{ $company['email'] }} or {{ $company['phone'] }}.
        </div>
    </div>
@endsection
