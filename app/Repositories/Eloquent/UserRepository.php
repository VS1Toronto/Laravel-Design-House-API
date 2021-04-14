<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Contracts\IUser;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class UserRepository extends BaseRepository implements IUser
{
    
    //-----------------------------------------------------------------------------------------------
    //  Commented out as this is now being pulled in through the BaseRepository
    //  Return the same results that you get in an all() query
    //
    //  public function all()
    //  {
    //      return User::all();
    //  }
    //-----------------------------------------------------------------------------------------------
	
	
	//-----------------------------------------------------------------------------------------------
    //  This returns the User Model to the BaseRepository
    //
    public function model()
    {
        return User::class; 
    }
    //-----------------------------------------------------------------------------------------------


    public function findByEmail($email)
    {
        return $this->model
                    ->where('email', $email)
                    ->first();
    }


    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();

        //  Only designers who have designs
        //
        if($request->has_designs){
            $query->has('designs');
        }

        //  Check for available_to_hire
        //
        if($request->available_to_hire){
            $query->where('available_to_hire', true);
        }

        //  Geographic Search
        //
        $lat = $request->latitude;
        $lng = $request->longitude;
        $dist = $request->distance;
        $unit = $request->unit;

        if($lat && $lng){
            $point = new Point($lat, $lng);
			
			//	Ternary Operator
            //  If the unit is in kilometers do this   *= 1000
            //  Or if the unit is not in kilometeres i.e. its miles do this   *=1609.34;
            //
            $unit == 'km' ? $dist *= 1000 : $dist *=1609.34;
            $query->distanceSphereExcludingSelf('location', $point, $dist);
        }

		//  Order the results
		//
        if($request->orderBy=='closest'){
            $query->orderByDistanceSphere('location', $point, 'asc');
        } else if($request->orderBy=='latest'){
            $query->latest();
        } else {
            $query->oldest();
        }

        return $query->get();
        
    }
}