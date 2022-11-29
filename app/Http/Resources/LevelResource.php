<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $units        = $this->whenLoaded('units');
        $traders      = $this->whenLoaded('traders');
        $construction = $this->whenLoaded('construction');
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'construction_id' => $this->construction_id,
            'construction'    => new ConstructionResource($construction),
            'units'           => UnitResource::collection($units),
            'traders'         => TraderResource::collection($traders),
        ];
    }
}
