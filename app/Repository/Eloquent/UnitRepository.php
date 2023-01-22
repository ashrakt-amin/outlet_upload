<?php

namespace App\Repository\Eloquent;

use App\Models\Unit;
use Illuminate\Support\Collection;
use App\Repository\UnitRepositoryInterface;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
    use TraitImageProccessingTrait;
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
        return $this->model->all();
    }

    /**
     * @return Collection
     */
    public function latest(): Collection
    {
        return $this->model->latest()->take(10)->get();
    }

    /**
     * @param array $attributes
     * @return Unit
     */
    public function create(array $attributes): Unit
    {
        $unit = $this->model->create($attributes);
        $unit->categories()->attach($attributes['category_id'], ['project_id'=> $unit->level->project_id]);
        $unit->unitImages()->createMany($this->setImages($attributes['img'], 'units', 'img', 450, 450));
        return $unit;
    }

    /**
     * @param $id
     * @return Unit
     */
    public function find($id): ?Unit
    {
        return $this->model->load(['items', 'trader'])->find($id);
    }

   /**
    * @param id $attributes
    * @return Unit
    */
    public function edit($id, array $attributes)
    {
        $unit = $this->model->findOrFail($id);
        $unit->update($attributes);
        $unit->categories()->syncWithPivotValues($attributes['category_id'], ['project_id' => $unit->level->project_id]);
        return $unit;
    }

    /**
     * @param id $attributes
     * @return Unit
     */
    public function toggleUpdate($id)
    {
        $unit = $this->model->find($id);
        $unit->update(['famous' => !$unit->famous]);
        return $unit;
    }

    /**
     * @return Collection
     */
    public function famous($random): Collection
    {
        // return $this->model->load(['items', 'trader'])->where(function($q) use($random){
        //     $random != 'random' ?: $q->inRandomOrder(6)->limit(6)->get();
        //     })->where(['famous' => false])->get();
        return $random == 'random' ?
            $this->model->load(['items', 'trader'])->where(['famous' => true])->inRandomOrder()->limit(6)->get()
        : $this->model->load(['items', 'trader'])->where(['famous' => true])->get();
    }
}
