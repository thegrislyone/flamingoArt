<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Chat\Messages;
use App\Models\Chat\Chats;

use App\Events\MessageSend;

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
            'to' => $to
        ])->only('chat', 'created_at', 'from', 'id', 'message_text', 'to');

        $message['channel'] = Chats::find($chat_id)->channel;

        event(new MessageSend($message));

        return $message;

    }

    public function getChatMessages(Request $request) {
        
        $first = $request['first'];
        $second = $request['to'];
        $chat_id = $request['chat_id'];

        $messages = Messages::where('chat', '=', $chat_id)->get(['chat', 'created_at', 'from', 'id', 'message_text', 'to']);

        return response()->json($messages, 200);

    }


    // public function findChatByInterlocutors($first, $second) {

    //     $chat_id;

    //     if (ChatUsers::where('user_first', '=', $first)->where('user_second', '=', $second)->first()) {

    //         // return "есть";
            
    //         $chat_id = ChatUsers::where('user_first', '=', $first)->where('user_second', '=', $second)->first()['id'];
        
    //     } elseif (ChatUsers::where('user_first', '=', $second)->where('user_second', '=', $first)->first()) {

    //         // return "есть, но перевернуто";
            
    //         $chat_id = ChatUsers::where('user_first', '=', $second)->where('user_second', '=', $first)->first()['id'];
        
    //     } else {
            
    //         // return "нет";

    //         $chat_id = ChatUsers::create([
    //             'user_first' => $first,
    //             'user_second' => $second
    //         ])['id'];

    //         Chats::create([
    //             'chat_id' => Str::random(16),
    //             'users' => $chat_id
    //         ]);
            
    //     }

    //     $chat = Chats::where('users', '=', $chat_id)->first();

    //     return $chat;
    // }

}
