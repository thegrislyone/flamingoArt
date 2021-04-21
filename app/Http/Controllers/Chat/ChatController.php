<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Chat\Messages;
use App\Models\Chat\MessagesConnecting;
use App\Models\Users;

class ChatController extends Controller
{
    public function sendMessage(Request $request) {
        return $request['message'];
    }
}
