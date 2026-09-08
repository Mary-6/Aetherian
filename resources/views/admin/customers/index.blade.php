@extends('layouts.admin')

@section('title', 'Client List')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <h1 class="text-2xl font-bold">Client List</h1>
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clients..." class="border rounded px-3 py-2 text-sm">
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
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-t">
                        <td class="px-6 py-3">{{ $customer->name }}</td>
                        <td class="px-6 py-3">{{ $customer->email }}</td>
                        <td class="px-6 py-3">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $customer->roles->pluck('name')->implode(', ') ?: 'Customer' }}</td>
                        <td class="px-6 py-3">{{ $customer->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No clients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $customers->links() }}</div>
@endsection
