@extends('layouts.admin')

@section('title', 'Chat: ' . ($room->guest_name ?: 'Guest'))

@section('content')
    <div class="bg-white rounded shadow flex flex-col h-[calc(100vh-180px)]">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="font-bold">{{ $room->guest_name ?: 'Guest' }}</h2>
                <p class="text-sm text-slate-500">{{ $room->guest_email ?: 'No email' }}@if ($room->guest_phone) &bull; {{ $room->guest_phone }}@endif &bull; {{ $room->room_id }}</p>
            </div>
            <a href="{{ route('admin.chat.index') }}" class="text-sm text-brand-600 hover:underline">Back to rooms</a>
        </div>

        <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-3">
            @forelse ($room->messages as $message)
                <div class="flex {{ $message->is_admin ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] px-4 py-2 rounded-xl {{ $message->is_admin ? 'bg-accent-500 text-navy rounded-br-none' : 'bg-slate-100 text-slate-900 rounded-bl-none' }}">
                        <p>{{ $message->content }}</p>
                        <span class="text-[10px] opacity-70 block mt-1">{{ $message->sender_name ?? ($message->is_admin ? 'Agent' : 'Guest') }} &bull; {{ $message->created_at->format('M d, H:i') }}</span>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-500">No messages yet.</p>
            @endforelse
        </div>

        <form action="{{ route('admin.chat.reply', $room) }}" method="POST" class="p-4 border-t flex items-center gap-2">
            @csrf
            <input type="text" name="content" placeholder="Type a reply..." class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-accent-500" required autofocus>
            <button type="submit" class="px-4 py-2 bg-navy text-white rounded-lg hover:bg-navy/90">Send</button>
        </form>
    </div>
@endsection
