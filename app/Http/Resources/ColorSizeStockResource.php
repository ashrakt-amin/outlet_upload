<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColorSizeStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $item            = $this->whenLoaded('item');
        return [
            'id'     => $this->id,
            'item'   => new ItemResource($item),
            'color'  => new ColorResource($this->color),
            'size'   => new SizeResource($this->size),
            'stock'  => $this->stock,
        ];
    }
}
