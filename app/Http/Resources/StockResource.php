<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserFullNameResource;
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
            'created_by'          => new UserFullNameResource($this->createdBy),
            'updated_by'          => new UserFullNameResource($this->updatedBy),
            'item'                => new ItemResource($item),
            'starting_stock'      => $this->starting_stock,
            'min_quantity'        => $this->min_quantity,
            'stock'               => $this->stock,
            'stock_code'          => $this->stock_code,
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
            'stock_discount'      => $this->stock_discount,
            'discount_start_date' => $this->discount_start_date,
            'discount_end_date'   => $this->discount_end_date,
        ];
    }
}
