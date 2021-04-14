<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Support\Arr;
use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;

//  This is an abstract class so you cant instatiate it on its own instead its extended by the other
//  repositories as it has the base classes used by all repositories to avoid duplication of common methods
//
abstract class BaseRepository implements IBase, ICriteria
{

    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    public function all()
    {
        return $this->model->get();
    }

    public function find($id)
    {
        $result = $this->model->findOrFail($id);
        return $result;
    }

    public function findWhere($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }
    

    public function findWhereFirst($column, $value)
    {
        return $this->model->where($column, $value)->firstOrFail();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function create(array $data)
    {
        $result = $this->model->create($data);
        return $result;
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }


    //-----------------------------------------------------------------------------------------------
    //  This is needed to accept the ICriteria Interface
    //
    public function withCriteria(...$criteria)
    {
        $criteria = Arr::flatten($criteria);

        foreach($criteria as $criterion){
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }
    //-----------------------------------------------------------------------------------------------


    //-----------------------------------------------------------------------------------------------
    //  This subclass gets the model from other repositories if they have one and populates
    //  the $model variable in this class with it through the constructor method which calls
    //  this function when this abstract class is instantiated though being extended from the
    //  other repositories
    //
    protected function getModelClass()
    {
        if( !method_exists($this, 'model'))
        {
            throw new ModelNotDefined();
        }

        return app()->make($this->model());

    }
    //-----------------------------------------------------------------------------------------------

   
}