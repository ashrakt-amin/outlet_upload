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
    * @param array $attributes
    * @return Collection
    */
    public function categoriesWhereHasNotParent(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('parent', $attributes) ?: $q
                ->where('parent_id', '<', '1');
            };
    }

   /**
    * @param array $attributes
    * @return Collection
    */
    public function categoriesWhereColumnName(array $attributes)
    {
        return function($q) use($attributes){
            !array_key_exists('columnName', $attributes) ?: $q
            ->where([$attributes['columnName'] => $attributes['columnValue']]);
        };
    }

   /**
    * @param array $attributes
    * @return Collection
    */
    public function categroiesForAllConditions(array $attributes)
    {
        return $this->model
            ->where($this->categoriesWhereHasNotParent($attributes))
            ->where($this->categoriesWhereColumnName($attributes))
            ->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "");
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
