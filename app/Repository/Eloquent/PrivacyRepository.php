<?php

namespace App\Repository\Eloquent;

use App\Models\Privacy;
use App\Repository\PrivacyRepositoryInterface;

class PrivacyRepository extends BaseRepository implements PrivacyRepositoryInterface
{
   /**
    * PrivacyRepository constructor.
    *
    * @param Privacy $model
    */
   public function __construct(Privacy $model)
   {
       parent::__construct($model);
   }
}
