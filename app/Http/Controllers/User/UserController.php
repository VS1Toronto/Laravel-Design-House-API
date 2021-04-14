<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;

class UserController extends Controller
{

    protected $users;
    

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    
    public function index()
    {
        //-------------------------------------------------------------------------------------------
        //  A   collection($users)   is used to return an entire collection instead of
        //  using   new UserResource($user)   which would be used to get a single object
        //
        //  $users = User::all();

        //  This is now doing the above by calling the all() method in the IUser Contract
        //
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();
        
        return UserResource::collection($users);
        //-------------------------------------------------------------------------------------------
    }


    public function search(Request $request)
    {
        $designers = $this->users->search($request);
        return UserResource::collection($designers);
    }


    public function findByUsername($username)
    {
        $user = $this->users->findWhereFirst('username', $username);
        return new UserResource($user);
    }
}
