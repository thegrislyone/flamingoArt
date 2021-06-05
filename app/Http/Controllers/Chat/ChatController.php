<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chat\Messages;
use App\Models\Chat\Chats;
use App\Models\Chat\ChatUsers;

use App\Models\User;

use App\Events\MessageSend;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    
    public function getChat(Request $request) {

        $to = $request['to'];
        $from = Auth::user()['id'];

        $chat = $this->findChatByInterlocutors($from, $to);

        return $chat->only('id', 'channel');
    }

    public function findChatByInterlocutors($first, $second) {

        $channel;

        if (ChatUsers::where('user_first', '=', $first)->where('user_second', '=', $second)->first()) {

            // return "есть";
            
            $channel = ChatUsers::where('user_first', '=', $first)->where('user_second', '=', $second)->first()['id'];
        
        } elseif (ChatUsers::where('user_first', '=', $second)->where('user_second', '=', $first)->first()) {

            // return "есть, но перевернуто";
            
            $channel = ChatUsers::where('user_first', '=', $second)->where('user_second', '=', $first)->first()['id'];
        
        } else {
            
            // return "нет";

            $channel = ChatUsers::create([
                'user_first' => $first,
                'user_second' => $second
            ])['id'];

            Chats::create([
                'channel' => Str::random(16),
                'users' => $channel
            ]);
            
        }

        $chat = Chats::where('users', '=', $channel)->first();

        return $chat;
    }

    // public function sendMessage(Request $request) {

    //     $message = $request['message'];
    //     $author = $request['author'];
    //     $destination = $request['interlocutor'];

    //     $chat = $this->findChatByInterlocutors($author, $destination);

    //     $message_id = Messages::create([
    //         'message_text' => $message,
    //         'chat' => $chat['id'],
    //         'interlocutors' => $chat['interlocutors']
    //     ]);

    //     $chat_room = $this->findChatByInterlocutors($author, $destination)['room_id'];

    //     event(new MessageSend($message, $author, $destination, $message_id, $chat_room));
        
    // }

    // public function getMessages(Request $request) {

    //     $author_id = $request['author'];
    //     $interlocutor_id = $request['interlocutor'];

    //     $channel = $this->findChatByInterlocutors($author_id, $interlocutor_id)['id'];

    //     $messages = Messages::where('chat', '=', $channel)->get();

    //     foreach ($messages as $message) {
    //         $interlocutors_id = $message['interlocutors'];
    //         $interlocutors = ChatUsers::find($interlocutors_id);
    //         $message['author'] = $interlocutors['user_first'];
    //         $message['interlocutor'] = $interlocutors['user_second'];
    //     }

    //     $response = [
    //         'messages' => $messages
    //     ];

    //     return response()->json($response, 200);
    // }

}
