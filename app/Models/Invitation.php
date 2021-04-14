<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'recipient_email',
        'sender_id',
        'team_id',
        'token'
    ];


    public function team()
    {
        return $this->belongsTo(Team::class);
    }


    //-----------------------------------------------------------------------
    //  This function states that each invitation   hasOne   recipient
    //  and that this recipient is linked to the   User Class   and that
    //  the Foreign Key on the User Table is going to be the   email   and
    //  that the Local Key is going to be the    recipient_email
    //
    public function recipient()
    {
        return $this->hasOne(User::class, 'email', 'recipient_email');
    }
    //-----------------------------------------------------------------------


    //-----------------------------------------------------------------------
    //  This function states that each invitation   hasOne   sender
    //  and that this sender is linked to the   User Class   and that
    //  the Foreign Key on the User Table is going to be the   id  and
    //  that the Local Key is going to be the    sender_id
    //
    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }
    //-----------------------------------------------------------------------



}
