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

class ChatController extends Controller
{

    public function getChatChannel(Request $request) {

        $interlocutor_id = $request['interlocutor_id'];
        $author_id = Auth::user()['id'];

        $chat = $this->findChatByInterlocutors($author_id, $interlocutor_id);

        return $chat['room_id'];
    }

    public function findChatByInterlocutors($first, $second) {

        $chat_id;

        if (ChatUsers::where('user_first', '=', $first)->where('user_second', '=', $second)->first()) {

            // return "есть";
            
            $chat_id = ChatUsers::where('user_first', '=', $first)->where('user_second', '=', $second)->first()['id'];
        
        } elseif (ChatUsers::where('user_first', '=', $second)->where('user_second', '=', $first)->first()) {

            // return "есть, но перевернуто";
            
            $chat_id = ChatUsers::where('user_first', '=', $second)->where('user_second', '=', $first)->first()['id'];
        
        } else {
            
            // return "нет";

            $chat_id = ChatUsers::create([
                'user_first' => $first,
                'user_second' => $second
            ])['id'];

            Chats::create([
                'room_id' => $this->generateChatId(16),
                'interlocutors' => $chat_id
            ]);
            
        }

        $chat = Chats::where('interlocutors', '=', $chat_id)->first();

        return $chat;
    }

    public function sendMessage(Request $request) {

        $message = $request['message'];
        $author = $request['author'];
        $destination = $request['interlocutor'];

        $chat = $this->findChatByInterlocutors($author, $destination);

        $message_id = Messages::create([
            'message_text' => $message,
            'chat' => $chat['id'],
            'interlocutors' => $chat['interlocutors']
        ]);

        $chat_room = $this->findChatByInterlocutors($author, $destination)['room_id'];

        event(new MessageSend($message, $author, $destination, $message_id, $chat_room));
        
    }

    public function getMessages(Request $request) {

        $author_id = $request['author'];
        $interlocutor_id = $request['interlocutor'];

        $chat_id = $this->findChatByInterlocutors($author_id, $interlocutor_id)['id'];

        $messages = Messages::where('chat', '=', $chat_id)->get();

        foreach ($messages as $message) {
            $interlocutors_id = $message['interlocutors'];
            $interlocutors = ChatUsers::find($interlocutors_id);
            $message['author'] = $interlocutors['user_first'];
            $message['interlocutor'] = $interlocutors['user_second'];
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
