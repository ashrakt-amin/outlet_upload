<?php

namespace App\Repository\Eloquent;

use App\Models\Item;
use App\Models\View;
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
        return $this->model->where(['project_id' => $project_id, 'parent_id' => $category_id])->where('discount', '>', 0)->get();
    }

    /**
     * @return Collection
     */
    public function itemWhereDiscount($columnName ,$columnvalue): Collection
    {
        return $this->model->where([$columnName => $columnvalue])->where('discount', '>', 0)->get();
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
        $item->update($attributes);
        return $item;
    }
}
