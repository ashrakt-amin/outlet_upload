<?php

namespace App\Repository\Eloquent;

use App\Models\Wishlist;
use Illuminate\Support\Collection;
use App\Repository\WishlistRepositoryInterface;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class WishlistRepository extends BaseRepository implements WishlistRepositoryInterface
{
    use TraitsAuthGuardTrait;
   /**
    * WishlistRepository constructor.
    *
    * @param Wishlist $model
    */
   public function __construct(Wishlist $model)
   {
       parent::__construct($model);
   }

   /**
    * @param array $attributes
    */
    public function index(array $attributes)
    {
        if (request()->bearerToken() != null) {
            $wishlists = $this->model->where(['client_id'=>$this->getTokenId('client')])->with(['item'])->get();
        } else {
            $wishlists = $this->model->where(['visitor_id'=>$attributes['visitor_id']])->get();
        }
        return $wishlists;
    }

   /**
    * @param array $attributes
    * @return Wishlist
    */
   public function toggleWishlist(array $attributes): Wishlist
   {
        if ($this->getTokenId('client')) {
            $attributes['client_id']  = $this->getTokenId('client');
            $wishlist = $this->model->where(['client_id' => $this->getTokenId('client'), 'item_id' => $attributes['item_id']])->first();
            $wishlist ? $wishlist->delete() : $wishlist = $this->model->create($attributes);
        } else {
            $wishlist = $this->model->orderBy('id', 'DESC')->first();
            if ($attributes['visitor_id'] == null) {$attributes['visitor_id'] = $wishlist ? $wishlist->id + 1 : 1;}
            $wishlist = $this->model->where(['visitor_id' => $attributes['visitor_id'], 'item_id' => $attributes['item_id']])->first();
            $wishlist ? $wishlist->delete() : $wishlist = $this->model->create($attributes);
        }
        return $wishlist;
    }

    /**
    * @param id $attributes
    * @return response
    */
    public function deleteAll(array $attributes): ?Wishlist
    {
        $wishlists = $this->getTokenId('client') ?
            $this->model->where(['client_id' => $this->getTokenId('client'), 'item_id' => $attributes['item_id']])->get()
            :
            $this->model->where(['visitor_id' => $attributes['visitor_id'], 'item_id' => $attributes['item_id']])->get();

        foreach ($wishlists as $wishlist) {
            $wishlist->delete();
        }
        return $wishlists;
    }
}
