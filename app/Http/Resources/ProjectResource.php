<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserFullNameResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'created_by'   => new UserFullNameResource($this->createdBy),
            'updated_by'   => new UserFullNameResource($this->updatedBy),
            'mainProject'  => new MainProjectResource($this->whenLoaded('mainProject')),
            'levels'       => LevelResource::collection($this->whenLoaded('levels')),
            'units'        => UnitResource::collection($this->whenLoaded('units')),
            'images'       => ProjectImageResource::collection($this->projectImages),
        ];
    }
}
