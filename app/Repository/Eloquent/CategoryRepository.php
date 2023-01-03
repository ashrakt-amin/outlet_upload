<?php

namespace App\Repository\Eloquent;

use App\Models\Category;
use Illuminate\Support\Collection;
use App\Repository\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
   /**
    * CategoryRepository constructor.
    *
    * @param Category $model
    */
   public function __construct(Category $model)
   {
       parent::__construct($model);
   }

   /**
    * Auth checking.
    *
    */
    public function checkAuth()
    {
        // $authorizationHeader = \request()->header('Authorization');
        // if(request()->bearerToken() != null) {
        //     return $this->middleware('auth:sanctum');
        // };
    }

}
