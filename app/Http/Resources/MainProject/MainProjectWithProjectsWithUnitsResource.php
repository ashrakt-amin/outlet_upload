<?php

namespace App\Http\Resources\MainProject;

use App\Http\Resources\Project\ProjectWithUintsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MainProjectWithProjectsWithUnitsResource extends JsonResource
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
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'projects'   => ProjectWithUintsResource::collection($this->projects()->distinct()->get()),
        ];
    }
}
