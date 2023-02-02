<?php

namespace App\Http\Resources;

use App\Models\LevelImage;
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
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'created_by'   => $this->createdBy,
            'updated_by'   => $this->updatedBy,
            'project'      => new ProjectResource($this->whenLoaded('project')),
            'project_id'   => $this->project->id,
            'project_name' => $this->project->name,
            'units'        => UnitResource::collection($this->whenLoaded('units')),
            'traders'      => TraderResource::collection($this->whenLoaded('traders')),
            'images'       => LevelImageResource::collection($this->levelImages),
        ];
    }
}
