<?php

namespace App\Http\Resources\Unit;

use App\Http\Resources\Category\CategoriesOnlyMainResource;
use App\Http\Resources\User\UserFullNameResource;
use App\Http\Resources\UnitImageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitWithoutItemsResource extends JsonResource
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
            'offers'       => $this->offers == 0 ? false : true,
            'firstImage'  => new UnitImageResource($this->unitImages()->first()),
            'secondImage' => new UnitImageResource($this->unitImages()->skip(1)->first()),
            'categories'  => CategoriesOnlyMainResource::collection($this->categories()->where(['parent_id' => 0])->get()),
        ];
    }
}
