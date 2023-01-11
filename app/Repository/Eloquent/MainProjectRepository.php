<?php

namespace App\Repository\Eloquent;

use App\Models\MainProject;
use Illuminate\Support\Collection;
use App\Repository\MainProjectRepositoryInterface;

class MainProjectRepository extends BaseRepository implements MainProjectRepositoryInterface
{
   /**
    * MainProjectRepository constructor.
    *
    * @param MainProject $model
    */
   public function __construct(MainProject $model)
   {
       parent::__construct($model);
   }

   /**
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->with(['projects'])->get();
    }

    /**
    * @param $id
    * @return MainProject
    */
    public function find($id): ?MainProject
    {
        return MainProject::where(['id'=>$this->model->id])->with(['projects'])->first();
    }
}
