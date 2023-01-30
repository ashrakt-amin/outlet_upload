<?php

namespace App\Repository\Eloquent;

use App\Models\Wishlist;
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
    * @return Wishlist
    */
   public function create(array $attributes): Wishlist
   {
    if ($this->getTokenId('client')) {
        $attributes['client_id']  = $this->getTokenId('client');
        $wishlist = Wishlist::where(['client_id' => $this->getTokenId('client'), 'item_id' => $attributes['item_id']])->first();
        $wishlist ? $wishlist->delete() : $wishlist = $this->model->create($attributes);
    } else {
        $wishlist = Wishlist::orderBy('id', 'DESC')->first();
        if ($attributes['visitor_id'] == null ) {$attributes['visitor_id'] = $wishlist ? $wishlist->id + 1 : 1;}
        // dd($attributes['visitor_id']);
        $wishlist = Wishlist::where(['visitor_id' => $attributes['visitor_id'], 'item_id' => $attributes['item_id']])->first();
        $wishlist ? $wishlist->delete() : $wishlist = $this->model->create($attributes);
    }
    return $wishlist;
    }
}
