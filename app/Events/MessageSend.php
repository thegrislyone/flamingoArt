<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message_text;
    public $author;
    public $interlocutor;
    public $id;
    public $chat_room;

    public function __construct($message, $author, $interlocutor, $message_id, $chat_room)
    {
        $this->author = $author;
        $this->interlocutor = $interlocutor;
        $this->message_text = $message;
        $this->id = $message_id;
        $this->chat_room = $chat_room;
    }

    public function broadcastOn()
    {
        return new Channel($this->chat_room);
        // return ['chat'];
    }

    public function broadcastAs() {
      return 'message-get';
    }
}
