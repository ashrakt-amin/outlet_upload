<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\ItemResource;
use App\Http\Resources\UnitImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitShowResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'famous'      => $this->famous == 0 ? false : true,
            'description' => $this->description,
            'images'      => UnitImageResource::collection($this->unitImages),
            'trader'      => [
                        'id' => $this->trader->id,
                        'name' => $this->trader->f_name. ' '. $this->trader->l_name,
                        'phone' => $this->trader->phone
                    ],
            'items'       => ItemResource::collection($this->unit_items),
            'categories'  => $this->unitCategories,
        ];
    }
}
