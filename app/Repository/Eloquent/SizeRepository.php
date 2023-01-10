<?php

namespace App\Repository\Eloquent;

use App\Models\Size;
use App\Repository\SizeRepositoryInterface;

class SizeRepository extends BaseRepository implements SizeRepositoryInterface
{
   /**
    * CategoryRepository constructor.
    *
    * @param Size $model
    */
   public function __construct(Size $model)
   {
       parent::__construct($model);
   }
}
