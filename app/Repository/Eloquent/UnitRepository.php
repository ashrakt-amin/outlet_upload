<?php

namespace App\Repository\Eloquent;

use App\Models\Unit;
use Illuminate\Support\Collection;
use App\Repository\UnitRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;
use App\Models\Level;

class UnitRepository extends BaseRepository implements UnitRepositoryInterface
{
    use TraitsAuthGuardTrait;
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
     * @param array $attributes
     * @return Unit
     */
    public function create(array $attributes): Unit
    {
        $attributes['project_id'] = Level::find($attributes['level_id'])->project_id;
        $attributes['created_by'] = $this->getTokenId('user');
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
        $attributes['updated_by'] = $this->getTokenId('user');
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
     * Method for units searching where key words
     */
    public function unitsSearchingWhereName(array $attributes)
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
            ->where($this->unitsWhereCategoryId($attributes))
            ->where($this->unitsSearchingWhereName($attributes))
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
    public function unitsForAllConditionsRandom(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->sendResponse(
                $this->resourceCollection::collection($this->unitsForAllConditions($attributes)->inRandomOrder()->limit($attributes['count'])->get()),
                "Random units; Youssof", 200);
        return $this->unitsForAllConditions($attributes)->inRandomOrder()->limit($attributes['count'])->get();
    }

    /**
     * Method for all units conditions to paginate
     */
    public function unitsForAllConditionsPaginate(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
        $this->paginateResponse(
            $this->resourceCollection::collection($this->unitsForAllConditions($attributes)->paginate($attributes['count'])),
            $this->unitsForAllConditions($attributes)->latest()->paginate($attributes['count']),
                "Paginate units; Youssof", 200);
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
    }
}
