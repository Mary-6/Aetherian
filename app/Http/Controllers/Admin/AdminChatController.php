<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Notifications\ChatReplyToCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminChatController extends Controller
{
    public function index()
    {
        $rooms = ChatRoom::with('lastMessage')
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.chat.index', compact('rooms'));
    }

    public function count()
    {
        $count = ChatRoom::sum('admin_unread_count');

        return response()->json(['count' => $count]);
    }

    public function show(ChatRoom $room)
    {
        $room->load(['messages.user']);

        if (! $room->assigned_to) {
            $room->assigned_to = auth()->id();
            $room->save();
        }

        $room->messages()
            ->where('is_admin', false)
            ->where('read_by_admin', false)
            ->update(['read_by_admin' => true]);

        $room->update(['admin_unread_count' => 0]);

        return view('admin.chat.show', compact('room'));
    }

    public function reply(Request $request, ChatRoom $room)
    {
        $data = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message = $room->messages()->create([
            'content' => $data['content'],
            'user_id' => auth()->id(),
            'is_admin' => true,
            'sender_name' => auth()->user()->name,
        ]);

        $room->last_message_at = now();
        if (! $room->assigned_to) {
            $room->assigned_to = auth()->id();
        }
        $room->save();

        $room->increment('guest_unread_count');

        try {
            $room->notify(new ChatReplyToCustomer($room, $message));
        } catch (\Throwable $e) {
            logger()->error('Failed to send customer web push notification', ['exception' => $e->getMessage()]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'id' => $message->id,
                'content' => $message->content,
                'is_admin' => true,
                'sender_name' => $message->sender_name,
                'created_at' => $message->created_at->toDateTimeString(),
            ]);
        }

        return back()->with('success', 'Reply sent.');
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'content_encoding' => 'nullable|string',
        ]);

        $request->user()->updatePushSubscription(
            $data['endpoint'],
            $data['keys']['p256dh'],
            $data['keys']['auth'],
            $data['content_encoding'] ?? null
        );

        return response()->json(['success' => true]);
    }
}
