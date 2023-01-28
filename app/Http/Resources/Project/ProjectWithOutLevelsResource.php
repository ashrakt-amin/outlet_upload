<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProjectImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Unit\UnitWithoutItemsResource;

class ProjectWithOutLevelsResource extends JsonResource
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
            'categories' => CategoryResource::collection($this->categories()->distinct()->get()),
            'units'      => UnitWithoutItemsResource::collection($this->units)->paginate(),
            'images'     => ProjectImageResource::collection($this->projectImages),
        ];
    }
}
