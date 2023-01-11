<?php

namespace App\Repository\Eloquent;

use App\Models\MainProject;
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
}
