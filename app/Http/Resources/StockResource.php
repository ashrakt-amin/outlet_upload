<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
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
            'id'     => $this->id,
            'item'   => new ItemResource($this->stock_item),
            'trader' => new TraderResource($this->stock_trader),
            'color'  => new ColorResource($this->stock_color),
            'size'   => new SizeResource($this->stock_size),
            'volume' => new VolumeResource($this->stock_volume),
            'weight' => new WeightResource($this->stock_weight),
            'season' => $this->stock_season,
            'stock'  => $this->stock,
        ];
    }
}
