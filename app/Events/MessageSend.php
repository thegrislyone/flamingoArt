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

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $chat;
    public $created_at;
    public $from;
    public $to;
    public $message_text;
    public $id;

    public $channel;

    public function __construct($message)
    {
        $this->chat = $message['chat'];
        $this->created_at = $message['created_at'];
        $this->from = $message['from'];
        $this->to = $message['to'];
        $this->message_text = $message['message_text'];
        $this->id = $message['id'];

        $this->channel = $message['channel'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    public function broadcastAs() {
        return 'message-get';
    }
}
