<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $category  = $this->whenLoaded('category');
        $groups    = $this->whenLoaded('groups');

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'category' => new CategoryResource($category),
            'groups'   => GroupResource::collection($this->subCategory_groups),
        ];
    }
}
