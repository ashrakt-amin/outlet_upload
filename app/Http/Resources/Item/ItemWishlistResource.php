<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemWishlistResource extends JsonResource
{
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
        return
        auth()->guard()->check()
        ? $this->wishlists()->where(['client_id'=>$this->getTokenId('client')])->count()
        : $this->wishlists()->where(['visitor_id'=>$request['visitor_id']])->count();
    }
}
