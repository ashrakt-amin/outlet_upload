<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\ItemResource;
use App\Http\Resources\ItemImageResource;
use App\Http\Resources\UnitImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitShowResource extends JsonResource
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
            'famous'      => $this->famous == 0 ? false : true,
            'description' => $this->description,
            'images'      => UnitImageResource::collection($this->unitImages),
            'trader'      => [
                        'id' => $this->trader->id,
                        'name' => $this->trader->f_name. ' '. $this->trader->l_name,
                        'phone' => $this->trader->phone
                    ],
            'items'       => [
                    'id'          => $this->id,
                    'name'        => $this->name,
                    'sale_price'  => $this->sale_price,
                    'discount'    => (float)$this->discount,
                    'flash_sales' => $this->flash_sales == 1 ? true : false,
                    'first_item_image' => new ItemImageResource($this?->itemImages()->first())
                ],
            'categories'  => $this->unitCategories,
        ];
    }
}
