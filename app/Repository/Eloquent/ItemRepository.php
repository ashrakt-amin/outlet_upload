<?php

namespace App\Repository\Eloquent;

use App\Models\Item;
use App\Models\Unit;
use App\Models\View;
use App\Models\Category;
use Illuminate\Support\Collection;
use App\Repository\ItemRepositoryInterface;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
{
    use TraitImageProccessingTrait;

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
    * @return Collection
    */
    public function search(array $attributes)
    {
        return $this->model->with(['unit'])->where(function($q) use($attributes){
            !array_key_exists('key_words', $attributes) ?: $q->where('key_words', 'LIKE', "%{$attributes['key_words']}%");
        })->get();
    }

    /**
     * @return Collection
     */
    public function latest(): Collection
    {
        return $this->model->with(['unit'])->latest()->take(10)->get();
    }

    /**
     * @return Collection
     */
    public function random(): Collection
    {
        return $this->model->with(['unit'])->inRandomOrder()->limit(6)->get();
    }

    /**
     * @return Collection
     */
    public function offerItemsOfCategoriesOfProject($project_id, $category_id): Collection
    {
        $categories = Category::where(['parent_id' => $category_id])->pluck('id')->all();
        $items = $this->model->where(['project_id' => $project_id])->whereIn('category_id', $categories)->where('discount', '>', 0)->get();
        return $items;
    }

    /**
     * @return Collection
     */
    public function itemWhereDiscountForAllConditions(array $attributes): Collection
    {
        return $this->model->where(function($q) use($attributes){
            !array_key_exists('columnName', $attributes) || $attributes['columnName'] == 0  ?: $q->where([$attributes['columnName'] => $attributes['columnValue']]);
        })->where('discount', '>', 0)->get();
    }

    /**
     * @param array $attributes
     * @return Item
     */
    public function create(array $attributes): Item
    {
        if ($attributes['buy_discount'] > 0) {
            $attributes['buy_discount'] = $attributes['sale_price'] - ($attributes['sale_price'] * $attributes['buy_discount'] / 100);
        } else {
            $attributes['buy_price'] = $attributes['buy_price'];
        }
        $attributes['level_id']   = Unit::find($attributes['unit_id'])->level_id;
        $attributes['project_id'] = Unit::find($attributes['unit_id'])->level->project_id;
        $item = $this->model->create($attributes);
        $item->itemImages()->createMany($this->setImages($attributes['img'], 'items', 'img',450, 450));
        return $item;
    }

    /**
     * @param $id
     * @return Item
     */
    public function find($id): ?Item
    {
        $item = $this->model->load(['stocks', 'unit'])->find($id);
        $user = $item->getTokenName('user');
        $trader = $item->getTokenName('trader');
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
        $attributes['level_id']   = $item->unit->level_id;
        $attributes['project_id'] = $item->unit->level->project_id;
        $item->update($attributes);
        return $item;
    }
}