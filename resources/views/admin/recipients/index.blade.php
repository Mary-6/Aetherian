@extends('layouts.admin')

@section('title', 'My Recipients')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h1 class="text-2xl font-bold">My Recipients</h1>
        <form action="{{ route('admin.recipients.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search recipients..." class="border rounded px-3 py-2 text-sm">
            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded text-sm hover:bg-blue-800">Search</button>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Address</th>
                    <th class="px-6 py-3">City / Country</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recipients as $recipient)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $recipient->recipient_name }}</td>
                        <td class="px-6 py-3">{{ $recipient->recipient_email }}</td>
                        <td class="px-6 py-3">{{ $recipient->recipient_phone ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $recipient->recipient_address ?? '-' }}</td>
                        <td class="px-6 py-3">{{ collect([$recipient->recipient_city, $recipient->recipient_country])->filter()->implode(', ') ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No recipients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $recipients->links() }}</div>
@endsection
