<?php

namespace App\Repository\Eloquent;

use App\Models\Category;
use App\Models\Project;
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
        return $this->model->where('parent_id', '<', '1')->get();
    }

    /**
     * Get categories of a project.
     *
     * @return Collection
     */
    public function categoriesOfProject($project_id)
    {
        return Category::whereHas('units', function($q) use($project_id) {
            $q->whereHas('level', function($q) use($project_id) {
                $q->whereHas('project', function($q) use($project_id) {
                    $q->where('id', $project_id);
                });
            });
        })->get();
    }
}
