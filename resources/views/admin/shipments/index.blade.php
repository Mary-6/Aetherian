@extends('layouts.admin')

@section('title', 'Shipments')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Shipments</h1>
        <a href="{{ route('admin.shipments.create') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800">Create Shipment</a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Tracking #</th>
                    <th class="px-6 py-3">Sender</th>
                    <th class="px-6 py-3">Recipient</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Created</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shipments as $shipment)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $shipment->tracking_number }}</td>
                        <td class="px-6 py-3">{{ $shipment->sender_name }}</td>
                        <td class="px-6 py-3">{{ $shipment->recipient_name }}</td>
                        <td class="px-6 py-3">{{ $shipment->status }}</td>
                        <td class="px-6 py-3">{{ $shipment->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3 space-x-2">
                            <a href="{{ route('admin.shipments.show', $shipment) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('admin.shipments.edit', $shipment) }}" class="text-blue-600 hover:underline">Edit</a>
                            <a href="{{ route('admin.shipments.invoice', $shipment) }}" target="_blank" class="text-green-600 hover:underline">Invoice</a>
                            <form action="{{ route('admin.shipments.destroy', $shipment) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center">No shipments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $shipments->links() }}</div>
@endsection
