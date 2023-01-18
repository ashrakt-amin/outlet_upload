<?php

namespace App\Http\Resources\Item;

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
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'sale_price'       => $this->sale_price,
            'discount'         => (float)$this->discount,
            'item_unit_name'   => $this->unit->name,
            'first_unit_image' => new UnitImageResource($this->unit?->unitImages()->first()),
            'flash_sales'      => $this->flash_sales == 1 ? true : false,
        ];
    }
}
