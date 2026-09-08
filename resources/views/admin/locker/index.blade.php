@extends('layouts.admin')

@section('title', 'Locker Packages')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h1 class="text-2xl font-bold">Locker Packages</h1>
        <form action="{{ route('admin.locker.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search packages..." class="border rounded px-3 py-2 text-sm">
            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">Search</button>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Tracking #</th>
                    <th class="px-6 py-3">Sender</th>
                    <th class="px-6 py-3">Recipient</th>
                    <th class="px-6 py-3">Package</th>
                    <th class="px-6 py-3">Weight</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Received</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($packages as $package)
                    @php $meta = $package->meta ?? []; @endphp
                    <tr class="border-t">
                        <td class="px-6 py-3 font-mono">{{ $package->tracking_number }}</td>
                        <td class="px-6 py-3">{{ $package->sender_name }}</td>
                        <td class="px-6 py-3">{{ $package->recipient_name }}</td>
                        <td class="px-6 py-3">{{ $meta['package_type'] ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $package->weight ? $package->weight . ' kg' : '-' }}</td>
                        <td class="px-6 py-3">{{ ucwords(str_replace(['_', '-'], ' ', $package->status)) }}</td>
                        <td class="px-6 py-3">{{ $package->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.shipments.show', $package) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-6 py-8 text-center text-slate-500">No packages in locker.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $packages->links() }}</div>
@endsection
