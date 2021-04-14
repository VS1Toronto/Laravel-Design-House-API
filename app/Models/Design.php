<?php

namespace App\Models;

use App\Models\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentTaggable\Taggable;

class Design extends Model
{
    use Taggable, Likeable;

    protected $fillable = [
        'user_id',
        'team_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk'
    ];


    //----------------------------------------------------------------------------------------------------
    //  USER BELONGS TO     This function when called means this model Design belongs to a User
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //----------------------------------------------------------------------------------------------------


    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    
    //----------------------------------------------------------------------------------------------------
    //  This is using morphMany() 
    //
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
                ->orderBy('created_at', 'asc');
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  GET IMAGES ATTRIBUTE     This passes $size to function GET IMAGES PATH
    //                           $Size corresponds to the folder directory names so they cant be changed
    //
    public function getImagesAttribute()
    {  
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'large' => $this->getImagePath('large'),
            'original' => $this->getImagePath('original'),
        ];
    }
    //----------------------------------------------------------------------------------------------------


    //----------------------------------------------------------------------------------------------------
    //  GET IMAGE PATH     This works by having $size passed in
    //
    protected function getImagePath($size)
    {
        return Storage::disk($this->disk)
                        ->url("uploads/designs/{$size}/".$this->image);
    }
    //----------------------------------------------------------------------------------------------------

}
