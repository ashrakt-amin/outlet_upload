<?php

namespace App\Repository\Eloquent;

use App\Models\Trader;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Repository\TraderRepositoryInterface;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class TraderRepository extends BaseRepository implements TraderRepositoryInterface
{
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;

   /**
    * TraderRepository constructor.
    *
    * @param Trader $model
    */
   public function __construct(Trader $model)
   {
       parent::__construct($model);
   }

    /**
    * @param array $attributes
    *
    * @return Trader
    */
    public function create(array $attributes): Trader
    {
        $img = $attributes['img'];
        $code = uniqueRandomCode('traders');
        return $this->model->create($attributes, [
            'code' => $code,
            'img'  => $this->setImage($img, 'traders', 450, 450)
        ]);
    }

    /**
    * @param $id
    * @return Trader
    */
    public function find($id): ?Trader
    {
        return $this->model->where(['id'=>$id])->with(['units'])->first();
    }

    /**
    * @param $id
    * @return Trader
    */
    public function edit($id, array $attributes)
    {
        $trader = $this->model;
        // dd($trader);
        if ($this->getTokenId('user') || $this->getTokenId('trader')) {
            return DB::transaction(function() use($trader, $attributes){
                $trader = $this->model->findOrFail($trader->id);
                $age = $trader->age;
                $trader->fill($attributes);
                if ($attributes['img']) {
                    $this->deleteImage(Trader::IMAGE_PATH, $trader->img);
                    $img = $attributes['img'];
                    $trader->img = $this->setImage($img, 'traders', 450, 450);
                }
                if ($attributes['age'] != null) {
                    $trader->age = $attributes['age'];
                } else {
                    $trader->age = $age;
                }
                $trader->update();
            });
        }
        return $trader;
    }

    /**
     * Display the trader for him resource.
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function trader()
    {
        $user = $this->getTokenId('trader');
        return Trader::where(['id'=>$user])->with(['units'])->first();
    }
}
