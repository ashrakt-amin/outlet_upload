<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'id'      => $this->id,
            'img'     => $this->path,
            'unit'    => [
                    'id'   => $this->unit_id,
                    'name' => $this->unit->name
                ],
            'trader'  => [
                    'id'   => $this->unit->trader_id,
                    'name' => $this->unit->trader->f_name . ' ' . $this->unit->trader->l_name
                ],
            'link'    => $this->link,
            "daysRemainig" => Carbon::now()->diffInDays($this->advertisement_expire, false),
        ];
    }
}
