@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-navy">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="text-slate-500">Here is what is happening across your logistics operations today.</p>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-500 mb-1">Total Shipments</p>
            <p class="text-3xl font-bold text-navy">{{ number_format($counts['shipments']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-500 mb-1">In Transit</p>
            <p class="text-3xl font-bold text-brand-600">{{ number_format($counts['in_transit']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-500 mb-1">Delivered</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($counts['delivered']) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-500 mb-1">Pending</p>
            <p class="text-3xl font-bold text-amber-500">{{ number_format($counts['pending']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-500 mb-1">Users</p>
            <p class="text-3xl font-bold text-navy">{{ number_format($counts['users']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-500 mb-1">Open Tickets</p>
            <p class="text-3xl font-bold text-red-500">{{ number_format($counts['tickets']) }}</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <p class="text-sm text-slate-500 mb-1">Open Chats</p>
            <p class="text-3xl font-bold text-navy">{{ number_format($counts['chat_rooms']) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent shipments --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 lg:col-span-2">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-navy">Recent Shipments</h3>
                <a href="{{ route('admin.shipments.index') }}" class="text-sm text-brand-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-6 py-3 font-medium">Tracking #</th>
                            <th class="px-6 py-3 font-medium">Recipient</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                            <th class="px-6 py-3 font-medium">Created</th>
                            <th class="px-6 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentShipments as $shipment)
                            <tr class="border-t border-slate-100 hover:bg-slate-50">
                                <td class="px-6 py-4 font-mono text-slate-700">{{ $shipment->tracking_number }}</td>
                                <td class="px-6 py-4">{{ $shipment->recipient_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($shipment->status === 'DELIVERED') bg-green-100 text-green-800
                                        @elseif($shipment->status === 'IN_TRANSIT') bg-blue-100 text-blue-800
                                        @elseif($shipment->status === 'PENDING') bg-amber-100 text-amber-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ $shipment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $shipment->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.shipments.show', $shipment) }}" class="text-brand-600 hover:underline font-medium">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No shipments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-8">
            {{-- Quick actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-navy">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.shipments.create') }}" class="block w-full text-center px-4 py-3 bg-navy text-white rounded-lg hover:bg-navy/90 transition-colors font-medium">Create Shipment</a>
                    <a href="{{ route('admin.users.create') }}" class="block w-full text-center px-4 py-3 bg-slate-100 text-slate-800 rounded-lg hover:bg-slate-200 transition-colors font-medium">Add User</a>
                    <a href="{{ route('admin.chat.index') }}" class="block w-full text-center px-4 py-3 bg-slate-100 text-slate-800 rounded-lg hover:bg-slate-200 transition-colors font-medium">Open Live Chat</a>
                </div>
            </div>

            {{-- Recent chat rooms --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-navy">Recent Chats</h3>
                    <a href="{{ route('admin.chat.index') }}" class="text-sm text-brand-600 hover:underline">All chats</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentChatRooms as $room)
                        <a href="{{ route('admin.chat.show', $room) }}" class="block px-6 py-4 hover:bg-slate-50">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-sm text-slate-900">{{ $room->guest_name ?: 'Guest' }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $room->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">{{ $room->status }}</span>
                            </div>
                            <p class="text-xs text-slate-500">{{ $room->last_message_at?->diffForHumans() ?? 'No messages' }}</p>
                        </a>
                    @empty
                        <div class="px-6 py-6 text-sm text-slate-500 text-center">No chats yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
