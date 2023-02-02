<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Http\Resources\User\UserFullNameResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Unit\UnitOfAdvertisementResource;

class AdvertisementResource extends JsonResource
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
            'img'        => $this->path,
            'created_by' => new UserFullNameResource($this->createdBy),
            'updated_by' => new UserFullNameResource($this->updatedBy),
            'unit'    => [
                    'id'   => $this->unit_id,
                    'name' => $this->unit->name
                ],
            'project'    => [
                    'id'   => $this->project_id,
                    'name' => $this->project->name
                ],
            'trader'  => [
                    'id'   => $this->unit->trader_id,
                    'name' => $this->unit->trader->f_name . ' ' . $this->unit->trader->l_name
                ],
            'link'       => $this->link,
            "daysRemainig" => Carbon::now()->diffInDays($this->advertisement_expire, false),
            "advertisementExpire" => $this->advertisement_expire,
            "renew" => $this->renew,
        ];
    }
}
