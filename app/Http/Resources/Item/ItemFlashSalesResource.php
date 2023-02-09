<?php

namespace App\Http\Resources\Item;

use App\Http\Resources\User\UserFullNameResource;
use App\Http\Resources\ItemImageResource;
use App\Http\Resources\UnitImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemFlashSalesResource extends JsonResource
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
            'created_by'  => new UserFullNameResource($this->createdBy),
            'updated_by'  => new UserFullNameResource($this->updatedBy),
            'sale_price'  => $this->sale_price,
            'discount'    => (float)$this->discount,
            'wishlist'    => $wishlist,
            'views'       => $this->all_views,
            'flash_sales' => $this->flash_sales == 1 ? true : false,
            'extra_piece' => $this->extra_piece == 1 ? true : false,
            'last_week'   => $this->last_week == 1 ? true : false,
            'whats_app_click' => $this->whatsAppClick,
            'first_item_image' => new ItemImageResource($this?->itemImages()->first()),
            'unit'        => [
                        'id'   => $this->unit?->id,
                        'name' => $this->unit?->name,
                        'first_unit_image'  => new UnitImageResource($this->unit?->unitImages()->first()),
                        'second_unit_image' => new UnitImageResource($this->unit?->unitImages()->skip(1)->first()),
            ],
        ];
    }
}
