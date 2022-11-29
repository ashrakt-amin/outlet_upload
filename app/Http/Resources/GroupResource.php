<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $types    = $this->whenLoaded('types');
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'types' => TypeResource::collection($this->group_types),
        ];
    }
}
