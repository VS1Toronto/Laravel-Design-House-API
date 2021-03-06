<?php

namespace App\Http\Controllers\Chats;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Repositories\Contracts\IChat;
use App\Http\Resources\MessageResource;
use App\Repositories\Contracts\IMessage;
use App\Repositories\Eloquent\Criteria\WithTrashed;

class ChatController extends Controller
{

    protected $chats;
    protected $messages;

    public function __construct(IChat $chats, IMessage $messages)
    {
        $this->chats = $chats;
        $this->messages = $messages;
    }

    //-------------------------------------------------------------------------------------
    //  Send message to user
    //
    public function sendMessage(Request $request)
    {
        //---------------------------------------------------------------------------------
        //  Validate the request from the user
        //
        $this->validate($request, [
            'recipient' => ['required'],
            'body' => ['required']
        ]);
        //---------------------------------------------------------------------------------

        $recipient = $request->recipient;
        $user = auth()->user();
        $body = $request->body;


        //---------------------------------------------------------------------------------
        //  Check if there is already a chat with that user
        //  and if there is append the message to that same chat
        //
        //  That means two users will never have more than one chat
        //  record in the system
        //
        $chat = $user->getChatWithUser($recipient);
        //---------------------------------------------------------------------------------


        //---------------------------------------------------------------------------------
        //  If there is not a chat :
        //
        //  create the chat record
        //  create the participants record via the Chat Repository
        //  
        //
        if(! $chat) {
            $chat = $this->chats->create([]);
            $this->chats->createParticipants($chat->id, [$user->id, $recipient]);
        }
        //---------------------------------------------------------------------------------
      

        //---------------------------------------------------------------------------------
        //  Add the message to the chat using the messages repository     
        //
        $message = $this->messages->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body,
            'last_read' => null
        ]);
        //---------------------------------------------------------------------------------
      
        
        return new MessageResource($message);

    }
    //-------------------------------------------------------------------------------------


    //  Get chats for user
    //
    public function getUserChats()
    {
        $chats = $this->chats->getUserChats();
        return ChatResource::collection($chats);
    }


    //  Get messages for chat
    //
    public function getChatMessages($id)
    {
        $messages = $this->messages->withCriteria([
                        new WithTrashed()
                    ])->findWhere('chat_id', $id);
        return MessageResource::collection($messages);
    }


    //  Mark chat as read
    //
    public function markAsRead($id)
    {
        $chat = $this->chats->find($id);
        $chat->markAsReadForUser(auth()->id());
        return response()->json(['message' => 'successful'], 200);
    }


    //  Destroy message
    //
    public function destroyMessage($id)
    {
        $message = $this->messages->find($id);
        $this->authorize('delete', $message);
        $message->delete();
    }


}
