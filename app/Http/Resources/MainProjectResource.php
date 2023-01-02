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
        $projects = $this->whenLoaded('projects');
        $levels  = $this->whenLoaded('levels');
        $units   = $this->whenLoaded('units');

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'projects' => ($projects),
            'levels'   => LevelResource::collection($levels),
            'units'    => UnitResource::collection($units),
        ];
    }
}
