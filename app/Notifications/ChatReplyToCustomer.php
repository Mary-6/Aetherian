<?php

namespace App\Notifications;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class ChatReplyToCustomer extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ChatRoom $room, public ChatMessage $message)
    {
    }

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $body = substr($this->message->content, 0, 120) . (strlen($this->message->content) > 120 ? '...' : '');

        return (new WebPushMessage())
            ->title('Aetherian Cargo support replied')
            ->body($body)
            ->icon('/brand-logo.png')
            ->badge('/brand-logo.png')
            ->data([
                'room_id' => $this->room->room_id,
                'url' => '/?chat_room=' . $this->room->room_id,
                'open_chat' => true,
            ])
            ->action('Open', 'open')
            ->tag("chat-room-{$this->room->room_id}");
    }
}
