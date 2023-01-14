<?php

namespace App\Repository\Eloquent;

use App\Models\Level;
use Illuminate\Support\Collection;
use App\Repository\LevelRepositoryInterface;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class LevelRepository extends BaseRepository implements LevelRepositoryInterface
{
    use TraitImageProccessingTrait;
   /**
    * LevelRepository constructor.
    *
    * @param Level $model
    */
   public function __construct(Level $model)
   {
       parent::__construct($model);
   }

   /**
   * @param array $attributes
   *
   * @return Level
   */
   public function create(array $attributes): Level
   {
       $level = $this->model->create($attributes);
       $level->levelImages()->createMany($this->setImages($attributes['img'], 'levels', 'img',450, 450));
       return $level;
   }

    /**
    * @param $id
    * @return Level
    */
    public function find($id): ?Level
    {
        return $this->model->with(['units', 'project'])->find($id);
    }
}
