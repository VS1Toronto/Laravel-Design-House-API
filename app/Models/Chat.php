<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //------------------------------------------------------------------
    //  SET RELATIONSHIPS BETWEEN TABLES
    //------------------------------------------------------------------
    //
    //  Get the participants for each chat
    //
    //  We have a diferent name for the intermediate table so we
    //  have to explicitly declare that name here 'participants'
    //
    //  If we dont do this Laravel will take the name of the two
    //  tables split by the intermediate table the chats table and
    //  the users table and give the intermediate table the name of
    //  chat_table   the name arranged in alphabetical order either
    //  side of the underscore
    //
    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
    }


    //  Get the messages for each chat
    //
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    //------------------------------------------------------------------


    //------------------------------------------------------------------
    //  HELPER METHOD
    //------------------------------------------------------------------
    //
    //  This is an accessor that comes with Laravel allowing us to
    //  create a method that retreives a particular attribute and this 
    //  creates a virtual attribute on this chat model called   
    //  LatestMessage
    //
    //  So we are pulling all the messages related to a particular chat
    //  ordering them with created at descending using the   latest()
    //  scope and then grabbing the first record
    //
    public function getLatestMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }
    //------------------------------------------------------------------


    public function isUnreadForUser($userId)
    {
        return (bool)$this->messages()
                ->whereNull('last_read')
                ->where('user_id', '<>', $userId)
                ->count();
    }


    public function markAsReadForUser($userId)
    {
        $this->messages()
            ->whereNull('last_read')
            ->where('user_id', '<>', $userId)
            ->update([
                'last_read' => Carbon::now()
            ]);
    }

}
