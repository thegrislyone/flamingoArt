<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chat\Messages;
use App\Models\Chat\Chats;

use App\Models\User;

use App\Events\MessageSend;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{

    public function getUserChats(Request $request) {

        $user = Auth::user()->id;

        $chats = Chats::where('user_first', '=', $user)->orWhere('user_second', '=', $user)->get(['channel', 'created_at', 'id', 'user_second', 'user_first'])->toArray();

        $chats = array_map(function($chat) use ($user) {

            if ($chat['user_first'] == $user) {

                $chat['user'] = User::find($chat['user_second']);

            } elseif ($chat['user_second'] == $user) {
                $chat['user'] = User::find($chat['user_first']);
            }
            
            // $chat['user'] = User::find($chat['user_second']);
            // unset($chat['user_second']);

            $chat['last_message'] = Messages::where('chat', '=', $chat['id'])->get()->toArray();
            $chat['last_message'] = end($chat['last_message']);

            $chat['unreaded_messages'] = count(Messages::where('chat', '=', $chat['id'])->where('checked', '=', 0)->get()->toArray());

            return $chat;

        }, $chats);

        
        
        return $chats;

    }
    
    public function getChat(Request $request) {

        $to = $request['to'];
        $from = Auth::user()['id'];

        $chat = $this->findChatByInterlocutors($from, $to);

        $chat = $chat->only('channel', 'created_at', 'id', 'user_second', 'user_first');

        if ($chat['user_first'] == $from) {

            $chat['user'] = User::find($chat['user_second']);

        } elseif ($chat['user_second'] == $from) {
            $chat['user'] = User::find($chat['user_first']);
        }

        unset($chat['user_second']);
        unset($chat['user_first']);

        $chat['last_message'] = Messages::where('chat', '=', $chat['id'])->get()->toArray();
        $chat['last_message'] = end($chat['last_message']);

        return $chat;
    }

    public function findChatByInterlocutors($first, $second) {

        $channel;

        if (Chats::where('user_first', '=', $first)->where('user_second', '=', $second)->first()) {
            
            $channel = Chats::where('user_first', '=', $first)->where('user_second', '=', $second)->first();
        
        } elseif (Chats::where('user_first', '=', $second)->where('user_second', '=', $first)->first()) {
            
            $channel = Chats::where('user_first', '=', $second)->where('user_second', '=', $first)->first();
        
        } else {

            $channel = Chats::create([
                'channel' => Str::random(16),
                'user_first' => $first,
                'user_second' => $second
            ]);
            
        }

        return $channel;
    }

}
