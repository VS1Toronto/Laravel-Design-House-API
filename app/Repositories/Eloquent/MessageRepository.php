<?php

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Contracts\IMessage;


//  This is implememnting an interface and so Laravel expects a concrete implementation of all
//  the methods that are listed in the interface and if that is not done then there will be errors
//
class MessageRepository extends BaseRepository implements IMessage
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
        return Message::class; 
    }
    //-----------------------------------------------------------------------------------------------

}
