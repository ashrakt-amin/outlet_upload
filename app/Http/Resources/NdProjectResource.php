<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserFullNameResource;

class NdProjectResource extends JsonResource
{
    function __construct(Project $model)
    {
        parent::__construct($model);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'created_by'  => new UserFullNameResource($this->createdBy),
            'updated_by'  => new UserFullNameResource($this->updatedBy),
            'mainProject' => new MainProjectResource($this->whenLoaded('mainProject')),
            'levels'      => $this->levels,
            'categories'  => CategoryResource::collection($this->categories()->distinct()->get()),
            'images'      => ProjectImageResource::collection($this->projectImages),
        ];
    }
}
