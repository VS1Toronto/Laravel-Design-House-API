<?php

namespace App\Repositories\Eloquent;

use App\Models\Chat;
use App\Repositories\Contracts\IChat;


//  This is implememnting an interface and so Laravel expects a concrete implementation of all
//  the methods that are listed in the interface and if that is not done then there will be errors
//
class ChatRepository extends BaseRepository implements IChat
{
    //-----------------------------------------------------------------------------------------------
    //  Commented out as this is now being pulled in through the BaseRepository
    //  Return the same results that you get in an all() query
    //
    //  public function all()
    //  {
    //      return User::all();
    //  }
    //----------------------------------------------------------------------------------------------- 


    //-----------------------------------------------------------------------------------------------
    //  This returns the User Model to the BaseRepository
    //
    public function model()
    {
        return Chat::class; 
    }
    //-----------------------------------------------------------------------------------------------


    public function createParticipants($chatId, array $data)
    {
        $chat = $this->model->find($chatId);

        //  Using sync() method to ensure users
        //  have only one chat and not more than that
        //
        $chat->participants()->sync($data);
    }

    
    public function getUserChats()
    {
        return auth()->user()->chats()
                    ->with(['messages', 'participants'])
                    ->get();

    }
    
}
