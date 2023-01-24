<?php

namespace App\Http\Resources;

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
        $mainProject  = $this->whenLoaded('mainProject');
        $levels        = $this->whenLoaded('levels');
        $units         = $this->whenLoaded('units');

        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'mainProject'  => new MainProjectResource($mainProject),
            'levels'       => LevelResource::collection($levels),
            'units'        => UnitResource::collection($units),
            'images'       => ProjectImageResource::collection($this->projectImages),
        ];
    }
}
