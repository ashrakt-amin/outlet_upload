<?php

namespace App\Repository\Eloquent;

use App\Models\Level;
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
        $attributes['created_by'] = $this->getTokenId('user');
        $level = $this->model->create($attributes);
        if (array_key_exists('img', $attributes)) $level->levelImages()->createMany($this->setImages($attributes['img'], 'levels', 'img', 450, 450));
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
