<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use App\Notifications\ChatMessageToAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ChatController extends Controller
{
    public function messages(Request $request)
    {
        $roomId = $request->input('room_id');
        if (! $roomId) {
            return response()->json(['room' => null, 'messages' => [], 'unread_count' => 0]);
        }

        $room = ChatRoom::where('room_id', $roomId)->first();
        if (! $room) {
            return response()->json(['room' => null, 'messages' => [], 'unread_count' => 0]);
        }

        $room->messages()
            ->where('is_admin', true)
            ->where('read_by_guest', false)
            ->update(['read_by_guest' => true]);

        $room->update(['guest_unread_count' => 0]);

        return response()->json([
            'room' => [
                'room_id' => $room->room_id,
                'guest_name' => $room->guest_name,
                'guest_email' => $room->guest_email,
            ],
            'unread_count' => 0,
            'messages' => $room->messages->map(fn ($m) => [
                'id' => $m->id,
                'content' => $m->content,
                'is_admin' => (bool) $m->is_admin,
                'sender_name' => $m->sender_name,
                'created_at' => $m->created_at->toDateTimeString(),
            ]),
        ]);
    }

    public function unread(Request $request)
    {
        $roomId = $request->input('room_id');
        if (! $roomId) {
            return response()->json(['count' => 0]);
        }

        $room = ChatRoom::where('room_id', $roomId)->first();

        return response()->json(['count' => $room ? $room->guest_unread_count : 0]);
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|string|max:100',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:50',
            'endpoint' => 'required|string',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'content_encoding' => 'nullable|string',
        ]);

        $room = ChatRoom::firstOrCreate(
            ['room_id' => $data['room_id']],
            [
                'guest_name' => $data['guest_name'] ?? 'Guest',
                'guest_email' => $data['guest_email'] ?? null,
                'guest_phone' => $data['guest_phone'] ?? null,
                'status' => 'open',
            ]
        );

        if (($data['guest_name'] ?? null) && ! $room->guest_name) {
            $room->guest_name = $data['guest_name'];
        }
        if (($data['guest_email'] ?? null) && ! $room->guest_email) {
            $room->guest_email = $data['guest_email'];
        }
        if (($data['guest_phone'] ?? null) && ! $room->guest_phone) {
            $room->guest_phone = $data['guest_phone'];
        }
        $room->save();

        $room->updatePushSubscription(
            $data['endpoint'],
            $data['keys']['p256dh'],
            $data['keys']['auth'],
            $data['content_encoding'] ?? null
        );

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|string|max:100',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:50',
            'content' => 'required|string|max:2000',
        ]);

        $room = ChatRoom::firstOrCreate(
            ['room_id' => $data['room_id']],
            [
                'guest_name' => $data['guest_name'] ?? 'Guest',
                'guest_email' => $data['guest_email'] ?? null,
                'guest_phone' => $data['guest_phone'] ?? null,
                'status' => 'open',
            ]
        );

        if (($data['guest_name'] ?? null) && $room->guest_name !== $data['guest_name']) {
            $room->guest_name = $data['guest_name'];
        }
        if (($data['guest_email'] ?? null) && ! $room->guest_email) {
            $room->guest_email = $data['guest_email'];
        }
        if (($data['guest_phone'] ?? null) && ! $room->guest_phone) {
            $room->guest_phone = $data['guest_phone'];
        }
        $room->last_message_at = now();
        $room->save();

        $message = $room->messages()->create([
            'content' => $data['content'],
            'is_admin' => false,
            'sender_name' => $room->guest_name,
        ]);

        $room->increment('admin_unread_count');

        try {
            $admins = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['Super Admin', 'Manager', 'Staff']))->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ChatMessageToAdmin($room, $message));
            }
        } catch (\Throwable $e) {
            logger()->error('Failed to send admin web push notification', ['exception' => $e->getMessage()]);
        }

        return response()->json([
            'id' => $message->id,
            'content' => $message->content,
            'is_admin' => false,
            'sender_name' => $message->sender_name,
            'created_at' => $message->created_at->toDateTimeString(),
        ]);
    }
}
