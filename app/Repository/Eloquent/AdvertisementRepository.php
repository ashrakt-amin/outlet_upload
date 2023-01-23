<?php

namespace App\Repository\Eloquent;

use App\Models\Advertisement;
use Illuminate\Support\Collection;
use App\Repository\AdvertisementRepositoryInterface;

class AdvertisementRepository extends BaseRepository implements AdvertisementRepositoryInterface
{
   /**
    * AdvertisementRepository constructor.
    *
    * @param Advertisement $model
    */
   public function __construct(Advertisement $model)
   {
       parent::__construct($model);
   }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        foreach ($this->model->all() as $advertisement) {
            $contruct_expire = Carbon::parse($advertisement->advertisement_expire)->diffForHumans();
            $diff_day = Carbon::now()->diffInDays($advertisement->advertisement_expire, false);
            if ($advertisement->advertisement_expire == date('Y-m-d') && $advertisement->renew > 0) {
                $advertisement->renew = $advertisement->renew - 1;
                $advertisement->update();
            } elseif ($contruct_expire == "1 week ago") {
                $advertisement->delete();
            }
        }
    }
}
