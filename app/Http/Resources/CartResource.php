<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $colorSizeStock   = $this->whenLoaded('colorSizeStock');
        $client           = $this->whenLoaded('client');
        $trader           = $this->whenLoaded('trader');
        return [
            'id'              => $this->id,
            'item'            => new ItemResource($this->item),
            'colorSizeStock'  => new ColorSizeStockResource($colorSizeStock),
            'client'          => new ClientResource($client),
            'trader'          => new TraderResource($trader),
            'quantity'        => $this->quantity,
        ];
    }
}
