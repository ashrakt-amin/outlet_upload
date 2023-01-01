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
        $project = $this->whenLoaded('project');
        $levels  = $this->whenLoaded('levels');
        $units   = $this->whenLoaded('units');

        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'project' => ProjectResource::collection($project),
            'levels'  => LevelResource::collection($levels),
            'units'   => UnitResource::collection($units),
        ];
    }
}
