<?php

namespace App\Repository\Eloquent;

use App\Models\TermsAndCondition;
use App\Repository\TermsAndConditionRepositoryInterface;

class TermsAndConditionRepository extends BaseRepository implements TermsAndConditionRepositoryInterface
{
   /**
    * TermsAndConditionRepository constructor.
    *
    * @param TermsAndCondition $model
    */
   public function __construct(TermsAndCondition $model)
   {
       parent::__construct($model);
   }
}
