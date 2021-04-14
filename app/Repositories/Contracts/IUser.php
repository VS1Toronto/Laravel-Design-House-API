<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

//  An interface just holds all the methods that need to be implemented in the repository
//
interface IUser
{
    //-----------------------------------------------------------------------------------------------
    //  This is no longer needed as it is being pulled in through the IBase Interface
    //
    //  public function all();
    //-----------------------------------------------------------------------------------------------

    public function findByEmail($email);
    public function search(Request $request);
}