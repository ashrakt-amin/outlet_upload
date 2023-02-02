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
        $wishlist =
        auth()->guard()->check()
        ? ($this->wishlists()->where(['item_id'=>$this->id, 'client_id'=>$this->getTokenId('client')])->exists() ? true : false)
        : ($this->wishlists()->where(['item_id'=>$this->id, 'visitor_id'=>$request['visitor_id']])->exists() ? true : false);
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'created_by'       => $this->createdBy,
            'updated_by'       => $this->updatedBy,
            'wishlist'         => $wishlist,
            'unit_name'        => $this->unit?->name,
            'item_project'     => $this->unit?->level?->project,
            'first_unit_image' => new UnitImageResource($this->unit?->unitImages()->first()),
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
            'unit'             => ($this->whenLoaded('unit')),
            'level_id'         => $this->level_id,
            'project_id'       => $this->project_id,
            'key_words'        => $this->key_words,
            'flash_sales'      => $this->flash_sales == 1 ? true : false,
            'extra_piece'      => $this->extra_piece == 1 ? true : false,
            'last_week'        => $this->last_week == 1 ? true : false,
            'trader'           => new TraderResource($this->unit?->trader),
            'clientViews'      => $this->client_views,
            'views'            => $this->all_views,
            'clientRate'       => $this->client_rate,
            'allRates'         => $this->all_rates,
            'category'         => $this->item_category,
            'colors'           => $this->item_colors,
            'sizes'            => $this->item_sizes,
            'volume'           => new VolumeResource($this->wieght),
            'stocks'           => StockResource::collection($this->whenLoaded('stocks')),
            'company'          => $this->company ? new CompanyResource($this->company) : false,
            'importer'         => $this->importer ? new ImporterResource($this->importer) : false,
            'manufactory'      => $this->manufactory ? new ManufactoryResource($this->manufactory) : false,
        ];
    }
}
