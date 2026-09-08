@extends('layouts.admin')

@section('title', 'Pickups')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h1 class="text-2xl font-bold">Pickups</h1>
        <form action="{{ route('admin.pickups.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pickups..." class="border rounded px-3 py-2 text-sm">
            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">Search</button>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Tracking #</th>
                    <th class="px-6 py-3">Sender</th>
                    <th class="px-6 py-3">Pickup Location</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Created</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pickups as $pickup)
                    @php
                        $pickupMeta = $pickup->meta ?? [];
                        $pickupStatus = $pickupMeta['pickup_status'] ?? $pickup->status;
                    @endphp
                    <tr class="border-t">
                        <td class="px-6 py-3 font-mono">{{ $pickup->tracking_number }}</td>
                        <td class="px-6 py-3">{{ $pickup->sender_name }}</td>
                        <td class="px-6 py-3">{{ $pickup->origin ?? '-' }}</td>
                        <td class="px-6 py-3">{{ ucwords(str_replace(['_', '-'], ' ', $pickupStatus)) }}</td>
                        <td class="px-6 py-3">{{ $pickup->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3">
                            <form action="{{ route('admin.pickups.update-status', $pickup) }}" method="POST" class="flex gap-2 items-center">
                                @csrf @method('PATCH')
                                <select name="status" class="border rounded px-2 py-1 text-sm">
                                    <option value="PENDING" @selected($pickupStatus === 'PENDING')>Pending</option>
                                    <option value="PICKED_UP" @selected($pickupStatus === 'PICKED_UP')>Picked Up</option>
                                    <option value="OUT_FOR_DELIVERY" @selected($pickupStatus === 'OUT_FOR_DELIVERY')>Out for Delivery</option>
                                    <option value="DELIVERED" @selected($pickupStatus === 'DELIVERED')>Delivered</option>
                                </select>
                                <button type="submit" class="text-blue-600 hover:underline text-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">No pickups scheduled.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $pickups->links() }}</div>
@endsection
