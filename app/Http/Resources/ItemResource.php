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
        $stocks   = $this->whenLoaded('stocks');
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'itemUnit'         => $this->item_unit,
            'itemImages'       => ItemImageResource::collection($this->itemImages),
            'unit_parts_count' => $this->unit_parts_count,
            'sale_price'       => (float)$this->sale_price,
            'code'             => $this->item_code,
            'available'        => $this->available ? true : false,
            'approved'         => $this->approved == 1 ? true : false,
            'description'      => $this->description ? $this->description : false,
            'discount'         => $this->discount ? (float)$this->discount : false,
            'trader'           => $this->item_trader,
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
            'import'           => $this->import ? true : false,
            'importer'         => $this->importer ? new ImporterResource($this->importer) : false,
            'manufactory'      => $this->manufactory ? new ManufactoryResource($this->manufactory) : false,
        ];
    }
}
