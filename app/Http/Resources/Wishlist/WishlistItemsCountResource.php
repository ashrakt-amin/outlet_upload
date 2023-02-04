<?php

namespace App\Http\Resources\Wishlist;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Item\ItemFlashSalesResource;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class WishlistItemsCountResource extends JsonResource
{
    use TraitsAuthGuardTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return
        // auth()->guard()->check()
        // ? ($this->wishlists()->where(['item_id'=>$this->id, 'client_id'=>$this->getTokenId('client')])->exists() ? true : false)
        // : ($this->wishlists()->where(['item_id'=>$this->id, 'visitor_id'=>$request['visitor_id']])->exists() ? true : false);
        return $this->count();
    }
}
