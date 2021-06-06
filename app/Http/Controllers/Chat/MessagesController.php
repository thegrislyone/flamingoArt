<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Chat\Messages;
use App\Models\Chat\Chats;
use App\Models\User;

use App\Events\MessageSend;
use App\Events\NotificationSend;

class MessagesController extends Controller
{
    
    public function sendMessage(Request $request) {
        
        $from = $request['from'];
        $to = $request['to'];
        $chat_id = $request['chat_id'];
        $message_text = $request['message'];

        $message = Messages::create([
            'message_text' => $message_text,
            'chat' => $chat_id,
            'from' => $from,
            'to' => $to,
            'checked' => false
        ])->only('chat', 'created_at', 'from', 'id', 'message_text', 'to');

        $message['channel'] = Chats::find($chat_id)->channel;

        event(new MessageSend($message));

        event(new NotificationSend([
            'type' => 'message',
            'data' => $message
        ], User::find($to)->common_notifications_channel));

        return $message;

    }

    public function getChatMessages(Request $request) {
        
        $first = $request['first'];
        $second = $request['to'];
        $chat_id = $request['chat_id'];

        $messages = Messages::where('chat', '=', $chat_id)->get(['chat', 'created_at', 'from', 'id', 'message_text', 'to', 'checked']);

        return response()->json($messages, 200);

    }

    public function checkMessage(Request $request) {

        $message_id = $request['message_id'];

        Messages::find($message_id)->update(['checked' => true]);

        return true;

    }


}
