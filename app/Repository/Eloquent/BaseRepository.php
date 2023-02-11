<?php

namespace App\Repository\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repository\EloquentRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class BaseRepository implements EloquentRepositoryInterface
{
    use TraitResponseTrait;
    use TraitsAuthGuardTrait;

    /**
     * @var Model
     */
     protected $model;

     /**
      * Resource Class
      */
     protected $resourceCollection;



    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
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
    *
    * @return Model
    */
    public function create(array $attributes): Model
    {
        $attributes['created_by'] = $this->getTokenId('user');
        return $this->model->create($attributes);
    }

    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

   /**
    * @param id $attributes
    * @return Model
    */
    public function edit($id, array $attributes)
    {
        $data = $this->model->findOrFail($id);
        $attributes['updated_by'] = $this->getTokenId('user');
        $data->update($attributes);
        return $data;
    }

    /**
    * @param $id
    * @return response
    */
    public function delete($id): ?Model
    {
        $data = $this->model->findOrFail($id);
        $data->delete();
        return $data;
    }

    /**
     * Write code on Method
    * @param $id
    * @return response
     */
    public function forceDelete($id)
    {
        $data = $this->model->onlyTrashed()->findOrFail($id);
        $data->forceDelete();
        return $data;


        return $this->model->onlyTrashed()->find($id)->forceDelete();
    }

    /**
     * Write code on Method
     *
    * @return response
     */
    public function forceDeleteAll()
    {
        $data = $this->model->onlyTrashed();
        $data->forceDelete();
        return $data;


        return $this->model->onlyTrashed()->forceDelete();
    }

    /**
     * Write code on Method
    * @param $id
    * @return Model
     */
    public function restore($id)
    {
        $data = $this->model->withTrashed()->findOrFail($id);
        $data->restore();
        return $data;


        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    /**
     * Write code on Method
     *
    * @return Collection
     */
    public function restoreAll(): Collection
    {
        $data = $this->model->onlyTrashed();
        $data->restore();
        return $data;


        return $this->model->onlyTrashed()->restore();
    }

    /**
     * Method for data conditions where column name
     */
    public function whereColumnName(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('columnName', $attributes) || $attributes['columnValue'] == 0  ?: $q
                ->where([$attributes['columnName'] => $attributes['columnValue']]);
            };
    }

    /**
     * Method for data conditions where boolean name
     */
    public function whereBooleanName(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('booleanName', $attributes)   ?: $q
                ->where([$attributes['booleanName'] => $attributes['booleanValue']]);
            };
    }

    /**
     * Method for data conditions where parent category id
     */
    public function whereCategoryParentId(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('parent_id', $attributes) || $attributes['parent_id'] == 0   ?: $q
                ->whereHas('category', function($q) use($attributes) {
                    $q->where(['parent_id' => $attributes['parent_id']]);
                });
            };
    }

    /**
     * Method for data conditions where category id
     */
    public function whereCategoryId(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('category_id', $attributes) || $attributes['category_id'] == 0   ?: $q
                ->where(['category_id' => $attributes['category_id']]);
            };
    }

    /**
     * Method for data searching where key words
     */
    public function searchingWherekeyWords(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('key_words', $attributes) ?: (!is_numeric($attributes['key_words']) ? $q
                ->where('key_words', 'LIKE', "%{$attributes['key_words']}%") : $q
                ->whereBetween('sale_price', [$attributes['key_words'] - 25, $attributes['key_words'] + 25]));
            };
    }

    /**
     * Method for data where unit boolean column
     */
    public function whereUnitBooleanColumn(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('unitBooleanColumn', $attributes) ?: $q
                ->whereHas('unit', function($q) use($attributes){
                    $q->where([$attributes['unitBooleanColumn'] => true]);
                });
            };
    }

    /**
     * Method for data where relation boolean column
     */
    public function whereRelationBooleanColumn(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('relationBooleanColumn', $attributes) ?: $q
                ->whereHas($attributes['relation'], function($q) use($attributes){
                    $q->where([$attributes['RelationBooleanColumn'] => true]);
                });
            };
    }

    /**
     * Method for data where discount
     */
    public function whereDiscount(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('discount', $attributes) ?: $q
                ->where('discount', '>', 0);
            };
    }

    /**
     * Method for data where discount
     */
    public function theLatest(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('latest', $attributes) ?: $q
                ->latest();
            };
    }

    /**
     * Method for all data conditions functional
     */
    public function forAllConditions(array $attributes)
    {
        return $this->model
            ->where($this->whereColumnName($attributes))
            ->where($this->whereBooleanName($attributes))
            ->where($this->whereCategoryParentId($attributes))
            ->where($this->whereCategoryId($attributes))
            ->where($this->searchingWherekeyWords($attributes))
            ->where($this->whereUnitBooleanColumn($attributes))
            ->where($this->whereDiscount($attributes))
            ->where($this->theLatest($attributes));
    }

    /**
     * Method for all data conditions to latest
     */
    public function forAllConditionsLatest(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)->latest()->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "")),
                $this->forAllConditions($attributes)->latest()->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : ""),
                "Latest data; Youssof", 200);
    }

    /**
     * Method for all data conditions to random
     */
    public function forAllConditionsRandom(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->sendResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)->inRandomOrder()->limit(array_key_exists('count', $attributes) ? $attributes['count'] : "")->get()),
                "Random data; Youssof", 200);
    }

    /**
     * Method for all data conditions to paginate
     */
    public function forAllConditionsPaginate(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "")),
                $this->forAllConditions($attributes)->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : ""),
                "paginate data; Youssof", 200);
    }

    /**
     * Method for all data conditions to return a wich method filtered by attributes
     */
    public function forAllConditionsReturn(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return array_key_exists('paginate', $attributes) ? $this->forAllConditionsPaginate($attributes, $resourceCollection)

            : (array_key_exists('latest', $attributes) ? $this->forAllConditionsLatest($attributes, $resourceCollection)

            : $this->forAllConditionsRandom($attributes, $resourceCollection));
    }

       /**
        * @param id $attributes
        * @return Item
        */
        public function toggleUpdate($id, $booleanName)
        {
            $item = $this->model->find($id);
            $item->update([$booleanName => !$item[$booleanName]]);
            return $item;
        }
}
