@extends('layouts.admin')

@section('title', 'Consolidated Shipments')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h1 class="text-2xl font-bold">Consolidated Shipments</h1>
        <form action="{{ route('admin.consolidated.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="shipment_type" value="{{ request('shipment_type') }}" placeholder="Filter by type..." class="border rounded px-3 py-2 text-sm">
            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Tracking #</th>
                    <th class="px-6 py-3">Sender</th>
                    <th class="px-6 py-3">Recipient</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Created</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $metaKey = 'shipment_type'; @endphp
                @forelse ($shipments as $shipment)
                    @php $meta = $shipment->meta ?? []; @endphp
                    <tr class="border-t">
                        <td class="px-6 py-3 font-mono">{{ $shipment->tracking_number }}</td>
                        <td class="px-6 py-3">{{ $shipment->sender_name }}</td>
                        <td class="px-6 py-3">{{ $shipment->recipient_name }}</td>
                        <td class="px-6 py-3">{{ $meta['shipment_type'] ?? '-' }}</td>
                        <td class="px-6 py-3">{{ ucwords(str_replace(['_', '-'], ' ', $shipment->status)) }}</td>
                        <td class="px-6 py-3">{{ $shipment->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.shipments.show', $shipment) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500">No consolidated shipments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $shipments->links() }}</div>
@endsection
