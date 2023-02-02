<?php

namespace App\Http\Resources\SubResources;

use App\Http\Resources\User\UserFullNameResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitItemOffersResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'created_by' => new UserFullNameResource($this->createdBy),
            'updated_by' => new UserFullNameResource($this->updatedBy),
            'items'      => $this->itemOffers,
            'categories' => $this->unitCategories,
        ];
    }
}
