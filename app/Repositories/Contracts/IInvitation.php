<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

//  An interface just holds all the methods that need to be implemented in the repository
//
interface IInvitation
{
    //-----------------------------------------------------------------------------------------------
    //  This is no longer needed as it is being pulled in through the IBase Interface
    //
    //  public function all();
    //-----------------------------------------------------------------------------------------------

    public function addUserToTeam($team, $user_id);
    public function removeUserFromTeam($team, $user_id);
}