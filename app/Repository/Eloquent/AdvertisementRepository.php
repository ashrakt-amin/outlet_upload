<?php

namespace App\Repository\Eloquent;

use Carbon\Carbon;
use App\Models\Advertisement;
use Illuminate\Support\Collection;
use App\Repository\AdvertisementRepositoryInterface;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class AdvertisementRepository extends BaseRepository implements AdvertisementRepositoryInterface
{
    use TraitImageProccessingTrait;

   /**
    * AdvertisementRepository constructor.
    *
    * @param Advertisement $model
    */
   public function __construct(Advertisement $model)
   {
       parent::__construct($model);
   }

    // /**
    //  * @return Collection
    //  */
    // public function all(): Collection
    // {
    //     foreach ($this->model->all() as $advertisement) {
    //         $contruct_expire = Carbon::parse($advertisement->advertisement_expire)->diffForHumans();
    //         $diff_day = Carbon::now()->diffInDays($advertisement->advertisement_expire, false);
    //         if ($advertisement->advertisement_expire == date('Y-m-d') && $advertisement->renew > 0) {
    //             $advertisement->renew = $advertisement->renew - 1;
    //             $advertisement->update();
    //         } elseif ($contruct_expire == "1 week ago") {
    //             $advertisement->delete();
    //         }
    //     }
    // }

    /**
     * @return Collection
     */
    public function advertisementsWhereColumnName(array $attributes)
    {
        return $this->model->where(function($q) use($attributes){
            !array_key_exists('columnName', $attributes) || $attributes['columnValue'] == 0  ?: $q
            ->where([$attributes['columnName'] => $attributes['columnValue']]);
        })->get();
    }

    /**
    * @param array $attributes
    *
    * @return Advertisement
    */
    public function create(array $attributes): Advertisement
    {
        $attributes['img'] = $this->setImage($attributes['img'], 'advertisements', 450, 450);
        $attributes['advertisement_expire'] = Carbon::parse(Carbon::now())->addDays($attributes['renew'] * 30);
        return $this->model->create($attributes);
    }

    /**
     * @param id $attributes
     * @return Advertisement
     */
     public function edit($id, array $attributes)
     {
        $advertisement = $this->model->findOrFail($id);
        $oldExpiryDate   = $advertisement->advertisement_expire;
        $month           = $oldExpiryDate[5].$oldExpiryDate[6];
        $oldRenew        = $advertisement->renew;
        $newRenew        = $attributes['renew'];
        // Expire of advertisement
        if ($attributes['renew'] > 0 ) {
            for ($i=0; $i < $newRenew; $i++) {
                $month = $month + 1;
                if ($month < 10) {
                    $month = '0'.$month;
                }
                if ($month > 12) {
                    $month = $month - 12;
                }
                $monthDays = Carbon::now()->month($month)->daysInMonth;
                $attributes['advertisement_expire'] = Carbon::parse($advertisement->advertisement_expire)->addDays(($monthDays));
            }
        }
        if ($attributes['img']) {
            $this->deleteImage(Advertisement::IMAGE_PATH, $advertisement->img);
            $attributes['img'] = $this->setImage($attributes['img'], 'advertisements', 450, 450);
        }
        $attributes['renew'] = $newRenew + $oldRenew;
        $advertisement->update($attributes);
        return $advertisement;
     }
}
