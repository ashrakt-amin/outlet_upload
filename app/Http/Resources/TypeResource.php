<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $group    = $this->whenLoaded('group');
        $items    = $this->whenLoaded('items');

        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'group' => new GroupResource($group),
            'items' => ItemResource::Collection($this->type_items),
        ];
    }
}
