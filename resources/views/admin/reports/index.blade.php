@extends('layouts.admin')

@section('title', 'General Reports')

@section('content')
    <h1 class="text-2xl font-bold mb-6">General Reports</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded shadow p-4">
            <p class="text-xs uppercase tracking-wide text-slate-500">Total Shipments</p>
            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalShipments) }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-xs uppercase tracking-wide text-slate-500">Total Clients</p>
            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalClients) }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-xs uppercase tracking-wide text-slate-500">Contact Messages</p>
            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalMessages) }}</p>
        </div>
        <div class="bg-white rounded shadow p-4">
            <p class="text-xs uppercase tracking-wide text-slate-500">Support Tickets</p>
            <p class="text-2xl font-bold text-blue-900">{{ number_format($totalTickets) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded shadow p-4">
            <h2 class="font-bold mb-3">Shipments by Status</h2>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50"><tr><th class="px-4 py-2">Status</th><th class="px-4 py-2">Count</th></tr></thead>
                <tbody>
                    @forelse ($statusCounts as $status => $count)
                        <tr class="border-t"><td class="px-4 py-2">{{ ucwords(str_replace(['_', '-'], ' ', $status)) }}</td><td class="px-4 py-2">{{ number_format($count) }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="px-4 py-3 text-slate-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded shadow p-4">
            <h2 class="font-bold mb-3">Shipments by Service</h2>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50"><tr><th class="px-4 py-2">Service</th><th class="px-4 py-2">Count</th></tr></thead>
                <tbody>
                    @forelse ($serviceCounts as $service => $count)
                        <tr class="border-t"><td class="px-4 py-2">{{ ucwords(str_replace(['_', '-'], ' ', $service)) }}</td><td class="px-4 py-2">{{ number_format($count) }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="px-4 py-3 text-slate-500">No data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded shadow p-4">
        <h2 class="font-bold mb-3">Recent Shipments</h2>
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Tracking #</th>
                    <th class="px-4 py-2">Sender</th>
                    <th class="px-4 py-2">Recipient</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentShipments as $shipment)
                    <tr class="border-t">
                        <td class="px-4 py-2 font-mono">{{ $shipment->tracking_number }}</td>
                        <td class="px-4 py-2">{{ $shipment->sender_name }}</td>
                        <td class="px-4 py-2">{{ $shipment->recipient_name }}</td>
                        <td class="px-4 py-2">{{ ucwords(str_replace(['_', '-'], ' ', $shipment->status)) }}</td>
                        <td class="px-4 py-2">{{ $shipment->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-3 text-slate-500">No shipments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
