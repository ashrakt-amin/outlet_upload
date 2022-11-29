<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $trader         = $this->whenLoaded('trader');
        $colorSizeStock = $this->whenLoaded('colorSizeStock');
        return [
            'id'             => $this->id,
            'order_id'       => $this->order_id,
            'trader_id'      => $this->trader_id,
            'discount'       => $this->discount,
            'quantity'       => $this->quantity,
            'itemPrice'      => $this->item_price,
            'orderStatu'     => new OrderStatuResource($this->orderStatu),
            'nextOrderStatu' => new OrderStatuResource($this->next_order_statu),
            'colorSizeStock' => new ColorSizeStockResource($colorSizeStock),
            'item'           => new ItemResource($this->item),
            'trader'         => new traderResource($trader),
        ];
    }
}
