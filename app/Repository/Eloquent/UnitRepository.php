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
     * Method for units conditions where column name
     */
    public function unitsWhereColumnName(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('columnName', $attributes) || $attributes['columnValue'] == 0  ?: $q
                ->where([$attributes['columnName'] => $attributes['columnValue']]);
            };
    }

    /**
     * Method for units conditions where boolean name
     */
    public function unitsWhereBooleanName(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('booleanName', $attributes)   ?: $q
                ->where([$attributes['booleanName'] => $attributes['booleanValue']]);
            };
    }

    /**
     * Method for units conditions where parent category id
     */
    public function unitsWhereCategoryParentId(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('parent_id', $attributes) || $attributes['parent_id'] == 0   ?: $q
                ->whereHas('category', function($q) use($attributes) {
                    $q->where(['parent_id' => $attributes['parent_id']]);
                });
            };
    }

    /**
     * Method for units conditions where category id
     */
    public function unitsWhereCategoryId(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('category_id', $attributes) || $attributes['category_id'] == 0   ?: $q
                ->whereHas('categories', function($q) use($attributes) {
                    $q->where(['category_id' => $attributes['category_id']]);
                });
            };
    }

    /**
     * Method for units conditions where category id
     */
    public function unitsWhereProjectId(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('project_id', $attributes) || $attributes['project_id'] == 0   ?: $q
                ->whereHas('level', function($q) use($attributes) {
                    $q->where(['project_id' => $attributes['project_id']]);
                });
            };
    }

    /**
     * Method for units searching where key words
     */
    public function unitsSearchingWherekeyWords(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('name', $attributes) ?: $q
                ->where('name', 'LIKE', "%{$attributes['name']}%");
            };
    }

    /**
     * Method for units where unit boolean column
     */
    public function unitsWhereUnitBooleanColumn(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('unitBooleanColumn', $attributes) ?: $q
                ->whereHas('unit', function($q) use($attributes){
                    $q->where([$attributes['unitBooleanColumn'] => true]);
                });
            };
    }

    /**
     * Method for all units conditions functional
     */
    public function unitsForAllConditions(array $attributes)
    {
        return $this->model
            ->where($this->unitsWhereColumnName($attributes))
            ->where($this->unitsWhereBooleanName($attributes))
            ->where($this->unitsWhereCategoryParentId($attributes))
            ->where($this->unitsWhereCategoryId($attributes))
            ->where($this->unitsSearchingWherekeyWords($attributes))
            ->where($this->unitsWhereUnitBooleanColumn($attributes));
    }

    /**
     * Method for all items conditions to latest
     */
    public function unitsForAllConditionsLatest(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->unitsForAllConditions($attributes)->latest()->paginate($attributes['count'])),
                $this->unitsForAllConditions($attributes)->latest()->paginate($attributes['count']),
                "Latest units; Youssof", 200);
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

        return array_key_exists('paginate', $attributes) ? $this->unitsForAllConditionsPaginate($attributes, $resourceCollection)

            : (array_key_exists('latest', $attributes) ? $this->unitsForAllConditionsLatest($attributes, $resourceCollection)

            : $this->unitsForAllConditionsRandom($attributes, $resourceCollection));
        $this->resourceCollection = $resourceCollection;
        return !array_key_exists('paginate', $attributes) ?
            $this->sendResponse($this->resourceCollection::collection($this->unitsForAllConditionsRandom($attributes)) , "Random units; Youssof", 200)
            :
            $this->paginateResponse($this->resourceCollection::collection($this->unitsForAllConditionsPaginate($attributes)), $this->unitsForAllConditionsPaginate($attributes), "paginate units; Youssof", 200);
    }
}
