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
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->where(['parent_id', '<', '1'])->get();
    }
}
