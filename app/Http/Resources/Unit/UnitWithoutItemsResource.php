<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\UnitImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitWithoutItemsResource extends JsonResource
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
            'id' => $this->id,
            'name'  => $this->name,
            'images' => UnitImageResource::collection($this->unitImages),
            'unit_categories' => $this->unitCategories,
        ];
    }
}
