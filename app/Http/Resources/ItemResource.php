<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $stocks = $this->whenLoaded('stocks');
        $unit   = $this->whenLoaded('unit');
        $trader = $this->whenLoaded('trader');
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'unit_name'        => $this->unit->name,
            'unit_images'      => UnitResource::collection($this->unit->unitImages),
            'itemUnit'         => $this->item_unit,
            'sale_price'       => $this->sale_price,
            'buy_price'        => $this->buy_price,
            'buy_discount'     => $this->buy_discount,
            'itemImages'       => ItemImageResource::collection($this->itemImages),
            'unit_parts_count' => $this->unit_parts_count,
            'code'             => $this->item_code,
            'available'        => $this->available ? true : false,
            'approved'         => $this->approved == 1 ? true : false,
            'description'      => $this->description ? $this->description : false,
            'discount'         => $this->discount ? (float)$this->discount : false,
            'unit'             => ($unit),
            'trader'           => new TraderResource($this->unit->trader),
            'clientViews'      => $this->client_views,
            'views'            => $this->all_views,
            'wishlist'         => $this->wishlist,
            'clientRate'       => $this->client_rate,
            'allRates'         => $this->all_rates,
            'category'         => $this->item_category,
            'colors'           => $this->item_colors,
            'sizes'            => $this->item_sizes,
            'volume'           => new VolumeResource($this->wieght),
            'stocks'           => StockResource::collection($stocks),
            'company'          => $this->company ? new CompanyResource($this->company) : false,
            'importer'         => $this->importer ? new ImporterResource($this->importer) : false,
            'manufactory'      => $this->manufactory ? new ManufactoryResource($this->manufactory) : false,
        ];
    }
}
