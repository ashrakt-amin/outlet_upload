<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'unit'    => new UnitResource($this->whenLoaded('unit')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'link'    => $this->link,
            "daysRemainig" => Carbon::now()->diffInDays($this->advertisement_expire, false),
        ];
    }
}
