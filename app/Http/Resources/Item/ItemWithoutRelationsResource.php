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
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'sale_price'  => $this->sale_price,
            'discount'    => (float)$this->discount,
            'flash_sales' => $this->flash_sales == 1 ? true : false,
            'first_item_image' => new ItemImageResource($this?->itemImages()->first()),
        ];
    }
}
