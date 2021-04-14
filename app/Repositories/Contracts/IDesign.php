<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

//  An interface just holds all the methods that need to be implemented in the repository
//
interface IDesign 
{
    //-----------------------------------------------------------------------------------------------
    //  This is no longer needed as it is being pulled in through the IBase Interface
    //
    //  public function all();
    //-----------------------------------------------------------------------------------------------

    public function applyTags($id, array $data);
    public function addComment($designId, array $data);
    public function like($id);
    public function isLikedByUser($id);
    public function search(Request $request);

}