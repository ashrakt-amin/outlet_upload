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
        $item   = $this->whenLoaded('item');
        return [
            'id'                  => $this->id,
            'item'                => new ItemResource($item),
            'starting_stock'      => $this->starting_stock,
            'min_quantity'        => $this->min_quantity,
            'stock'               => $this->stock,
            'stock_code'          => $this->stock_code,
            'buy_price'           => $this->buy_price,
            'buy_discount'        => $this->buy_discount,
            'sale_price'          => $this->sale_price,
            'available'           => $this->available,
            'manufacture_date'    => $this->manufacture_date,
            'expire_date'         => $this->expire_date,
            'trader'              => new TraderResource($this->trader),
            'color'               => new ColorResource($this->color),
            'size'                => new SizeResource($this->size),
            'volume'              => new VolumeResource($this->volume),
            'season'              => $this->stock_season,
            'barcode'             => $this->barcode,
            'spare_barcode'       => $this->spare_barcode,
            'discount'            => $this->discount,
            'discount_start_date' => $this->discount_start_date,
            'discount_end_date'   => $this->discount_end_date,
        ];
    }
}
