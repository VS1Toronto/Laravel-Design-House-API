<?php

namespace App\Repositories\Contracts;

//  An interface just holds all the methods that need to be implemented in the repository
//
interface IBase
{
    //  Each of these methods are implemented in the Base Repository
    //
    public function all();
    public function find($id);
    public function findWhere($column, $value);
    public function findWhereFirst($column, $value);
    public function paginate($perPage = 10);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}