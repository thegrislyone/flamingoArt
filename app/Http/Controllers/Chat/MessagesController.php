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

    /**
     * @OA\POST(
     *      path="/api/messages/send-message",
     *      operationId="sendMessage",
     *      tags={"Chat"},
     *      summary="Send message",
     *      description="Returns message",
     *      @OA\Parameter(
     *          name="to",
     *          description="from",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="from",
     *          description="to",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="chat_id",
     *          description="to",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="message_text",
     *          description="to",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="message",
     *                  type="array",
     *                  @OA\Items(
     *                      type="string",
     *                  )
     *              ),
     *          )
     *       )
     *     )
     */
    
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

    /**
     * @OA\GET(
     *      path="/api/messages/get-chat-messages",
     *      operationId="geChatMessages",
     *      tags={"Chat"},
     *      summary="Get chat-messages",
     *      description="Returns chat messages",
     *      @OA\Parameter(
     *          name="first",
     *          description="from",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="to",
     *          description="to",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="chat_id",
     *          description="to",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="messages",
     *                  type="array",
     *                  @OA\Items(
     *                      type="string",
     *                  )
     *              ),
     *          )
     *       )
     *     )
     */

    public function getChatMessages(Request $request) {
        
        $first = $request['first'];
        $second = $request['to'];
        $chat_id = $request['chat_id'];

        $messages = Messages::where('chat', '=', $chat_id)->get(['chat', 'created_at', 'from', 'id', 'message_text', 'to', 'checked']);

        return response()->json($messages, 200);

    }

    /**
     * @OA\POST(
     *      path="/api/messages/check-message",
     *      operationId="checkMessage",
     *      tags={"Chat"},
     *      summary="Check message",
     *      description="Method that checks message and return status",
     *      @OA\Parameter(
     *          name="message_id",
     *          description="from",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="status",
     *                  type="boolen",
     *              ),
     *          )
     *       )
     *     )
     */

    public function checkMessage(Request $request) {

        $message_id = $request['message_id'];

        Messages::find($message_id)->update(['checked' => true]);

        return true;

    }


}
