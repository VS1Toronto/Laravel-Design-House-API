<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class MeController extends Controller
{
    public function getMe()
    {
        //---------------------------------------------------------------------------------------
        //  If there is an autheticated user this will return a user
        //  object and if there is not then this will return a null object
        //
        if(auth()->check()){

            //  Commented out when started using UserResource
            //
            //  return response()->json(["user" => auth()->user()], 200);

            $user = auth()->user();
            return new UserResource($user);
        }
        return response()->json(null, 401);
        //---------------------------------------------------------------------------------------
    }
}
