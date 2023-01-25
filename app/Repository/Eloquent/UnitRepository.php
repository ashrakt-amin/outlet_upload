<?php

namespace App\Repository\Eloquent;

use App\Models\Unit;
use Illuminate\Support\Collection;
use App\Repository\UnitRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
    use TraitResponseTrait;
    use TraitImageProccessingTrait;

    /**
     * Resource Class
     */
    protected $resourceCollection;

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
    public function toggleUpdate($id, $booleanName)
    {
        $unit = $this->model->find($id);
        $unit->update([$booleanName => !$unit[$booleanName]]);
        return $unit;
    }

    /**
     * @return Collection
     */
    public function unitsForAllConditions(array $attributes)
    {
        return $this->model->where(function($q) use($attributes){
            !array_key_exists('columnName', $attributes) || $attributes['columnValue'] == 0  ?: $q
            ->where([$attributes['columnName'] => $attributes['columnValue']]);
            })
            ->where(function($q) use($attributes){
                !array_key_exists('booleanName', $attributes)   ?: $q
                ->where($attributes['booleanName'], $attributes['booleanValue']);
            })
            ->where(function($q) use($attributes){
                !array_key_exists('name', $attributes) ?: $q
                ->where('name', 'LIKE', "%{$attributes['name']}%");
            });
    }

    /**
     * Method for all units conditions to random
     */
    public function unitsForAllConditionsRandom(array $attributes)
    {
        return $this->unitsForAllConditions($attributes)->inRandomOrder()->limit($attributes['count'])->get();
    }

    /**
     * Method for all units conditions to paginate
     */
    public function unitsForAllConditionsPaginate(array $attributes)
    {
        return $this->unitsForAllConditions($attributes)->paginate($attributes['count']);
    }

    /**
     * Method for all units conditions to return a random or paginated array
     */
    public function unitsForAllConditionsReturn(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;
        return !array_key_exists('paginate', $attributes) ?
            $this->sendResponse($this->resourceCollection::collection($this->unitsForAllConditionsRandom($attributes)) , "Random units; Youssof", 200)
            :
            $this->paginateResponse($this->resourceCollection::collection($this->unitsForAllConditionsPaginate($attributes)), $this->unitsForAllConditionsPaginate($attributes), "paginate units; Youssof", 200);
    }
}
