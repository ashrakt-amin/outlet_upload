<?php

namespace App\Http\Resources;

use App\Http\Resources\Item\ItemFlashSalesResource;
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
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'subCategories'  => CategoryResource::collection($this->category_sub_categories),
            'parentCategory' => $this->parent_category,
            'items'          => ItemFlashSalesResource::collection($this->items),
        ];
    }
}
