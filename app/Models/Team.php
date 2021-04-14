<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        //----------------------------------------------------------------------------------------------------
        //  When team is created add current user as team member
        //
        static::created(function($team){

            //  First way of doing this
            //
            //  auth()->user()->teams()->attach($team->id);

            //  Second way of doing this
            //
            $team->members()->attach(auth()->id());
        });

        //  Static boot method for deleting a team including the deletion
        //  of any intermediate table records for the membership of that team
        //
        static::deleting(function($team){
            $team->members()->sync([]);
        });
        //----------------------------------------------------------------------------------------------------
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function members()
    {
        return $this->belongsToMany(User::class)
                ->withTimestamps();
    }

    public function designs()
    {
        return $this->hasMany(Design::class);
    }


    //----------------------------------------------------------------------------------------------------
    //  This helper function when called checks if a team has a particular user
    //
    public function hasUser(User $user)
    {
        return $this->members()
                    ->where('user_id', $user->id)
                    ->first() ? true : false;
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This helper function defines a relationship between teams and invitations 
    //
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This helper checks to see if a team has a pending ivitation for a particular email
    //
    public function hasPendingInvite($email)
    {
        return (bool)$this->invitations()
                        ->where('recipient_email', $email)
                        ->count();
    }
    //----------------------------------------------------------------------------------------------------

}
