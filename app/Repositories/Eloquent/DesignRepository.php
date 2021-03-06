<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use Illuminate\Http\Request;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\BaseRepository;


//  This is implememnting an interface and so Laravel expects a concrete implementation of all
//  the methods that are listed in the interface and if that is not done then there will be errors
//
class DesignRepository extends BaseRepository implements IDesign
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
        return Design::class; 
    }
    //-----------------------------------------------------------------------------------------------



    public function applyTags($id, array $data)
    {
        $design = $this->find($id);
        $design->retag($data);
    }


    public function addComment($designId, array $data)
    {
        //  Get the design for which we want to create a comment
        //
        $design = $this->find($designId);

        //  Create the comment for the new design
        //
        $comment = $design->comments()->create($data);

        return $comment;
    }


    public function like($id)
    {
        $design = $this->model->findOrFail($id);
        if($design->isLikedByUser(auth()->id())){
            $design->unlike();
        } else {
            $design->like();
        }

        return $design->likes()->count();
    }


    public function isLikedByUser($id)
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(auth()->id());
    }


    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();
        $query->where('is_live', true);


        //  Return only designs with comments
        //
        if($request->has_comments){
            $query->has('comments');
        }

        //  Return only designs assigned to teams
        //
        if($request->has_team){
            $query->has('team');
        }

        //  Search title and description for provided string
        //
        if($request->q){
            $query->where(function($q) use ($request){
                $q->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%');
            });
        }

        //  Order the query by likes or latest first
        //
        if($request->orderBy=='likes'){
            $query->withCount('likes') // likes_count
                ->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        return $query->with('user')->get();
    }
    
}