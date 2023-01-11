<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MainProjectResource extends JsonResource
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
            'id'       => $this->id,
            'name'     => $this->name,
            'projectsOfProject' => $this->projectsOfProject,
            'projects' => NdProjectResource::collection($this->projects),
            'levels'   => LevelResource::collection($levels),
            'units'    => UnitResource::collection($units),
        ];
    }
}
