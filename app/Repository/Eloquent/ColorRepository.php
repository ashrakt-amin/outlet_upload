<?php

namespace App\Repository\Eloquent;

use App\Models\Color;
use App\Repository\ColorRepositoryInterface;

class ColorRepository extends BaseRepository implements ColorRepositoryInterface
{
   /**
    * ColorRepository constructor.
    *
    * @param Color $model
    */
   public function __construct(Color $model)
   {
       parent::__construct($model);
   }
}
