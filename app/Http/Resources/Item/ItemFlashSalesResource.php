<?php

namespace App\Http\Resources\Item;

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
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'sale_price'  => $this->sale_price,
            'discount'    => (float)$this->discount,
            'wishlist'    => $this->wishlist == 1 ? true : false,
            'flash_sales' => $this->flash_sales == 1 ? "الازالة من العروض السريعة" : "الاضافة للعروض السريعة",
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
