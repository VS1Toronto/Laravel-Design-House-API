<?php

namespace App\Repositories\Eloquent;

use App\Models\Team;
use App\Repositories\Contracts\ITeam;
use App\Repositories\Eloquent\BaseRepository;


//  This is implememnting an interface and so Laravel expects a concrete implementation of all
//  the methods that are listed in the interface and if that is not done then there will be errors
//
class TeamRepository extends BaseRepository implements ITeam
{
    //-----------------------------------------------------------------------------------------------
    //  Commented out as this is now being pulled in through the BaseRepository
    //  Return the same results that you get in an all() query
    //
    //  public function all()
    //  {
    //      return Design::all();
    //  }
    //-----------------------------------------------------------------------------------------------


    //-----------------------------------------------------------------------------------------------
    //  This returns the Design Model to the BaseRepository
    //
    public function model()
    {
        return Team::class; 
    }
    //-----------------------------------------------------------------------------------------------


    public function fetchUserTeams()
    {
        return auth()->user()->teams;
    }

}