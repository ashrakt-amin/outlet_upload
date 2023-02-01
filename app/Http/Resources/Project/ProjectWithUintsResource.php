<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Unit\UnitWithoutItemsResource;

class ProjectWithUintsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'         => $this->id,
            'name'       => $this->name,
            'units'      => UnitWithoutItemsResource::collection($this->units()->distinct()->inRandomOrder()->limit($request['count'])->get())
        ];
    }
}
