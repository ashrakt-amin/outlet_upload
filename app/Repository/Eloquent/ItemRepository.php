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
            $view = View::where(['item_id'=>$id, 'client_id'=>$this->getTokenId('client')])->first();
            if (!$view) {
                $item->views()->create(['client_id' => $this->getTokenId('client'), 'view_count' => 1, 'item_id' => $id ]);
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
}
