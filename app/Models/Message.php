<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    //-----------------------------------------------------------------------
    //  This flags a model as deleted utilising a
    //  column for the model in the table which will 
    //  contain   deleted at   but does not actually
    //  delete it from the database
    //
    use SoftDeletes;
    //-----------------------------------------------------------------------


    //-----------------------------------------------------------------------
    //  If a new message is sent to a chat then this is dealing with 
    //  updating the timestamp for that chat and indicating when the
    //  chat was last updated through the messages
    //
    //  It does this by monitoring the save actions on the messages
    //  model and and updating the chat relationship each time there
    //  is a save on the message
    //
    protected $touches=['chat'];
    //-----------------------------------------------------------------------


    protected $fillable=[
        'user_id', 
        'chat_id', 
        'body', 
        'last_read'
    ];


    //-----------------------------------------------------------------------
    //  This function is an Access Modifier or Accessor
    //  An Accessor modifies the value of a targetted field in a Model 
    //  Its targetting the   body   attribute in the fillable array above
    //
    public function getBodyAttribute($value)
    {
        //  If this is deleted or trashed in Laravel language
        //  then we want to modify the field to have a different value
        //
        if($this->trashed()){
            if(!auth()->check()) return null;

            return auth()->id() == $this->sender->id ?
                    'You deleted this message' :
                    "{$this->sender->name} deleted this message";
        }

        //  If this is not deleted or trashed in Laravel language
        //  then just return the fields original value without modifying
        //
        return $value;
    }
    //-----------------------------------------------------------------------


    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }


    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
