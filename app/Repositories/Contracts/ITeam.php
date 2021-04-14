<?php

namespace App\Repositories\Contracts;

//  An interface just holds all the methods that need to be implemented in the repository
//
interface ITeam 
{
    //-----------------------------------------------------------------------------------------------
    //  This is no longer needed as it is being pulled in through the IBase Interface
    //
    //  public function all();
    //-----------------------------------------------------------------------------------------------

    public function fetchUserTeams();
}