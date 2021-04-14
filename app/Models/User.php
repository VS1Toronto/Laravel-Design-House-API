<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SpatialTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'tagline',
        'about',
        'username',
        'formatted_address',
        'available_to_hire',
        'location'
    ];


    protected $spatialFields = [
        'location',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $appends=[
        'photo_url'
    ];


    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=200&d=mm';
    }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    //----------------------------------------------------------------------------------------------------
    //  This function when called means this model User has many Designs
    //
    public function designs()
    {
        return $this->hasMany(Design::class);
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function when called means this model User has many Comments
    //
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function when called means the teams the user belongs to
    //
    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->withTimestamps();
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function when called means the teams the user actually created
    //
    public function ownedTeams()
    {
        return $this->teams()
            ->where('owner_id', $this->id);
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function when called gets a list of the teams a user created
    //
    public function isOwnerOfTeam($team)
    {
        return (bool)$this->teams()
                        ->where('id', $team->id)
                        ->where('owner_id', $this->id)
                        ->count();
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function when called deals with the relationships for invitations that belong
    //  to a user so this function returns the invitations waiting for them to accept or deny
    //
    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'recipient_email', 'email');
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function gets the chats that the user belongs to
    //
    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'participants');
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function gets the messages for a chat taht the user belongs to
    //
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  This function gets the messages for a chat if two users belong to that chat which is the way
    //  this chat system is coded i.e. only two users at a time can belong to a chat and if a chat doesnt
    //  exist between two users then that chat between the users will have to created before the messages
    //  associated with that chat between the two users can be loaded up
    //
    //  Here we pass the   $user_id   as a paramater which allows the function to check
    //  if the current user already has a chat with the user whos id we are passing through
    //
    public function getChatWithUser($user_id)
    {
        $chat = $this->chats()
                    ->whereHas('participants', function($query) use ($user_id) {
                        $query->where('user_id', $user_id);
                    })
                    ->first();  //  Get first entry  
        
        return $chat;   //  Returns null if no chat exists leaving us to create it
    }
    //----------------------------------------------------------------------------------------------------

  
    //----------------------------------------------------------------------------------------------------
    //  JWT FUNCTIONS NECESSARY AS THE JWTSUBJECT CONTRACT HAS NOW BEEN IMPELEMENTED IN THIS USER MODEL
    //
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    //----------------------------------------------------------------------------------------------------

}
