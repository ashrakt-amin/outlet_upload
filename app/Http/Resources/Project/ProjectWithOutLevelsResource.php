<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\User\UserFullNameResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProjectImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Unit\UnitWithoutItemsResource;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class ProjectWithOutLevelsResource extends JsonResource
{
    use TraitResponseTrait;
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
            'created_by' => new UserFullNameResource($this->createdBy),
            'updated_by' => new UserFullNameResource($this->updatedBy),
            'categories' => CategoryResource::collection($this->categories()->distinct()->get()),
            'units'      => UnitWithoutItemsResource::collection($this->units)->paginate($request->count ?: 24),
            'images'     => ProjectImageResource::collection($this->projectImages),
        ];
    }
}
