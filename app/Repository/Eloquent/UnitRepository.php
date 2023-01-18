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
        return $this->model->inRandomOrder()->limit(6)->get();
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
        $unit->categories()->attach($attributes['category_id'], ['trader_id'=> $unit->trader_id]);
        $unit->level->project->categories()->attach($attributes['category_id'], ['project_id'=> $unit->level->project_id]);
        $unit->unitImages()->createMany($this->setImages($attributes['img'], 'units', 'img',450, 450));
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
        $data = $this->model->findOrFail($id);
        $data->update($attributes);
        $data->categories()->updateExistingPivot($attributes['category_id'], ['trader_id' => $data->trader_id]);
        // $data->level->project->categories()->syncWithPivotValues($attributes['category_id'], ['trader_id' => $data->trader_id]);
        $data->level->project->categories()->updateExistingPivot($attributes['category_id'], ['trader_id' => $data->trader_id]);
        return $data;
    }
}
