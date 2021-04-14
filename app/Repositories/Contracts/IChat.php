<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

//  An interface just holds all the methods that need to be implemented in the repository
//
interface IChat
{
    //-----------------------------------------------------------------------------------------------
    //  This is no longer needed as it is being pulled in through the IBase Interface
    //
    //  public function all();
    //-----------------------------------------------------------------------------------------------


    public function createParticipants($chatId, array $data);
    public function getUserChats();

}