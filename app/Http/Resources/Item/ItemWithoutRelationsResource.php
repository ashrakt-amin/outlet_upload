<?php

namespace App\Http\Resources\Item;

use App\Http\Resources\ItemImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemWithoutRelationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $wishlist =
        auth()->guard()->check()
        ? ($this->wishlists()->where(['item_id'=>$this->id, 'client_id'=>$this->getTokenId('client')])->exists() ? true : false)
        : ($this->wishlists()->where(['item_id'=>$this->id, 'visitor_id'=>$request['visitor_id']])->exists() ? true : false);
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'created_by'  => $this->created_by()->f_name,
            'updated_by'  => $this->updated_by()->f_name,
            'sale_price'  => $this->sale_price,
            'discount'    => (float)$this->discount,
            'wishlist'    => $wishlist,
            'flash_sales' => $this->flash_sales == 1 ? true : false,
            'first_item_image' => new ItemImageResource($this?->itemImages()->first()),
        ];
    }
}
