<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $items = $this->whenLoaded('items');
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'subCategories'  => CategoryResource::collection($this->category_sub_categories),
            'parentCategory' => $this->parent_category,
            'items'          => ItemResource::collection($this->category_items),
        ];
    }
}
