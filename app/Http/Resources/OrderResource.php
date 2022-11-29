<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $orderDetails   = $this->whenLoaded('orderDetails');
        $client         = $this->whenLoaded('client');
        $finance        = $this->whenLoaded('finance');
        $orderStatu     = $this->whenLoaded('orderStatu');
        return [
            'id'              => $this->id,
            'address'         => $this->address,
            'finance_id'      => $this->finance_id,
            'total'           => $this->total,
            'discount'        => $this->discount,
            'orderDetails'    => OrderDetailResource::collection($orderDetails),
            'orderStatu'      => new OrderStatuResource($this->orderStatu),
            'client'          => new ClientResource($client),
            'finance'         => new FinanceResource($finance),
            'orderStatu'      => new OrderStatuResource($orderStatu),
        ];
    }
}
