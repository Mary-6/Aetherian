<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class ChatRoom extends Model
{
    use HasFactory, HasPushSubscriptions, Notifiable;

    protected $fillable = ['room_id', 'guest_name', 'guest_email', 'guest_phone', 'guest_unread_count', 'admin_unread_count', 'status', 'assigned_to', 'last_message_at'];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at');
    }

    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getRouteKeyName()
    {
        return 'room_id';
    }
}
