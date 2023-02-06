<?php

namespace App\Repository\Eloquent;

use App\Models\Item;
use App\Models\Unit;
use App\Models\View;
use App\Repository\ItemRepositoryInterface;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
    use TraitsAuthGuardTrait;
    use TraitResponseTrait;
    use TraitImageProccessingTrait;

    /**
     * Resource Class
     */
    protected $resourceCollection;

    /**
     * ItemRepository constructor.
     *
     * @param Item $model
     */
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }
    /**
     * @param array $attributes
     * @return Item
     */
    public function create(array $attributes): Item
    {
        $attributes['created_by'] = $this->getTokenId('user');
        $attributes['level_id']   = Unit::find($attributes['unit_id'])->level_id;
        $attributes['project_id'] = Unit::find($attributes['unit_id'])->level->project_id;
        $item = $this->model->create($attributes);
        $item->itemImages()->createMany($this->setImages($attributes['img'], 'items', 'img', 450, 450));
        return $item;
    }

    /**
     * @param $id
     * @return Item
     */
    public function find($id): ?Item
    {
        $item = $this->model->load(['stocks', 'unit'])->find($id);
        $user = $this->getTokenName('user');
        $trader = $this->getTokenName('trader');
        if (!$user && !$trader) {
            $view = View::where(['item_id'=>$item->id, 'client_id'=>$item->getTokenId('client')])->first();
            if (!$view) {
                $item->views()->create(['client_id' => $item->getTokenId('client'), 'view_count' => 1, 'item_id' => $item->id ]);
            } elseif ($view) {
                $view->view_count = $view->view_count + 1;
                $view->update();
            }
        }
        return $item;
    }

    /**
     * @param id $attributes
     * @return Item
     */
    public function edit($id, array $attributes)
    {
        $item = $this->model->findOrFail($id);
        $attributes['updated_by'] = $this->getTokenId('user');
        $attributes['level_id']   = $item->unit->level_id;
        $attributes['project_id'] = $item->unit->level->project_id;
        $item->update($attributes);
        return $item;
    }

    /**
     * Method for items conditions where column name
     */
    public function itemsWhereColumnName(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('columnName', $attributes) || $attributes['columnValue'] == 0  ?: $q
                ->where([$attributes['columnName'] => $attributes['columnValue']]);
            };
    }

    /**
     * Method for items conditions where boolean name
     */
    public function itemsWhereBooleanName(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('booleanName', $attributes)   ?: $q
                ->where([$attributes['booleanName'] => $attributes['booleanValue']]);
            };
    }

    /**
     * Method for items conditions where parent category id
     */
    public function itemsWhereCategoryParentId(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('parent_id', $attributes) || $attributes['parent_id'] == 0   ?: $q
                ->whereHas('category', function($q) use($attributes) {
                    $q->where(['parent_id' => $attributes['parent_id']]);
                });
            };
    }

    /**
     * Method for items conditions where category id
     */
    public function itemsWhereCategoryId(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('category_id', $attributes) || $attributes['category_id'] == 0   ?: $q
                ->where(['category_id' => $attributes['category_id']]);
            };
    }

    /**
     * Method for items searching where key words
     */
    public function itemsSearchingWherekeyWords(array $attributes)
    {
        // dd($attributes['key_words'] + 25);
        return function($q) use($attributes){
                !array_key_exists('key_words', $attributes) ?: (!(int)$attributes['key_words'] ? $q
                ->where('key_words', 'LIKE', "%{$attributes['key_words']}%") : $q
                // ->where('sale_price', 'LIKE', "%{$attributes['key_words']}%")
                ->whereBetween('sale_price', [$attributes['key_words'] - 25, $attributes['key_words'] + 25]));
            };
    }

    /**
     * Method for items where unit boolean column
     */
    public function itemsWhereUnitBooleanColumn(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('unitBooleanColumn', $attributes) ?: $q
                ->whereHas('unit', function($q) use($attributes){
                    $q->where([$attributes['unitBooleanColumn'] => true]);
                });
            };
    }

    /**
     * Method for items where discount
     */
    public function itemsWhereDiscount(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('discount', $attributes) ?: $q
                ->where('discount', '>', 0);
            };
    }

    /**
     * Method for items where discount
     */
    public function itemsLatest(array $attributes)
    {
        return function($q) use($attributes){
                !array_key_exists('latest', $attributes) ?: $q
                ->latest();
            };
    }

    /**
     * Method for all items conditions functional
     */
    public function itemsForAllConditions(array $attributes)
    {
        return $this->model
            ->where($this->itemsWhereColumnName($attributes))
            ->where($this->itemsWhereBooleanName($attributes))
            ->where($this->itemsWhereCategoryParentId($attributes))
            ->where($this->itemsWhereCategoryId($attributes))
            ->where($this->itemsSearchingWherekeyWords($attributes))
            ->where($this->itemsWhereUnitBooleanColumn($attributes))
            ->where($this->itemsWhereDiscount($attributes))
            ->where($this->itemsLatest($attributes));
    }

    /**
     * Method for all items conditions to latest
     */
    public function itemsForAllConditionsLatest(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->itemsForAllConditions($attributes)->latest()->paginate($attributes['count'])),
                $this->itemsForAllConditions($attributes)->latest()->paginate($attributes['count']),
                "Latest items; Youssof", 200);
    }

    /**
     * Method for all items conditions to random
     */
    public function itemsForAllConditionsRandom(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->sendResponse(
                $this->resourceCollection::collection($this->itemsForAllConditions($attributes)->inRandomOrder()->limit($attributes['count'])->get()),
                "Random items; Youssof", 200);
    }

    /**
     * Method for all items conditions to paginate
     */
    public function itemsForAllConditionsPaginate(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->itemsForAllConditions($attributes)->paginate($attributes['count'])),
                $this->itemsForAllConditions($attributes)->paginate($attributes['count']),
                "paginate items; Youssof", 200);
    }

    /**
     * Method for all items conditions to return a wich method filtered by attributes
     */
    public function itemsForAllConditionsReturn(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return array_key_exists('paginate', $attributes) ? $this->itemsForAllConditionsPaginate($attributes, $resourceCollection)

            : (array_key_exists('latest', $attributes) ? $this->itemsForAllConditionsLatest($attributes, $resourceCollection)

            : $this->itemsForAllConditionsRandom($attributes, $resourceCollection));
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
