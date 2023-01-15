<?php

namespace App\Repository\Eloquent;

use App\Models\Unit;
use Illuminate\Support\Collection;
use App\Repository\UnitRepositoryInterface;

class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
   /**
    * UnitRepository constructor.
    *
    * @param Unit $model
    */
   public function __construct(Unit $model)
   {
       parent::__construct($model);
   }

   /**
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->inRandomOrder()->limit(6)->get();
    }

}
