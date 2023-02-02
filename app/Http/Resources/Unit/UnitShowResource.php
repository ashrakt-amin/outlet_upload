<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\UnitImageResource;
use App\Http\Resources\User\UserFullNameResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Item\ItemFlashSalesResource;

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
            'created_by'  => new UserFullNameResource($this->createdBy),
            'updated_by'  => new UserFullNameResource($this->updatedBy),
            'famous'      => $this->famous == 0 ? false : true,
            'online'       => $this->online == 0 ? false : true,
            'offers'       => $this->offers == 0 ? false : true,
            'description' => $this->description,
            'images'      => UnitImageResource::collection($this->unitImages),

            'trader'       => [
                    'id' => $this->trader->id,
                    'name' => $this->trader->f_name. ' '. $this->trader->l_name,
                    'phone' => $this->trader->phone
                ],

            'items'        => ItemFlashSalesResource::collection($this->unit_items)->paginate(9),
            'categories'  => $this->unitCategories,
        ];
    }
}
