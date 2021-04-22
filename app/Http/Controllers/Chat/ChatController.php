<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chat\Messages;
use App\Models\Chat\MessagesConnecting;
use App\Models\Users;

use App\Events\MessageSend;

use Illuminate\Support\Facades\Hash;

class ChatController extends Controller
{
    public function sendMessage(Request $request) {

        $message = $request['message'];
        $author = $request['author'];
        $destination = $request['interlocutor'];
        $chat_room = $request['chat_room'];

        $message_id = Messages::create([
            'message_text' => $message
        ]);

        $message_id = $message_id['id'];

        MessagesConnecting::create([
            'author_id' => $author,
            'message_id' => $message_id,
            'interlocutor_id' => $destination,
            'chat_room' => $chat_room
        ]);

        event(new MessageSend($message, $author, $destination, $message_id, $chat_room));
        
    }

    public function getMessages(Request $request) {

        $author_id = $request['author'];
        $interlocutor_id = $request['interlocutor'];

        $messagesConnections = MessagesConnecting::where('author_id', '=', $author_id)->where('interlocutor_id', '=', $interlocutor_id)->get();

        $messages = [];

        foreach($messagesConnections as $messagesConnection) {
            $msg = Messages::find($messagesConnection['message_id'])->only('id', 'message_text');
            $msg['author'] = $messagesConnection['author_id'];
            $msg['interlocutor'] = $messagesConnection['interlocutor_id'];
            array_push($messages, $msg);
        }

        $response = [
            'messages' => $messages
        ];

        return response()->json($response, 200);
    }

    public function generateChatId($length) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
