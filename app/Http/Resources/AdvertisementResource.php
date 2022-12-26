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
        $trader = $this->whenLoaded('trader');
        $expiry_date =  $this->renew > 0 ? $this->renew * 30 : 30;
        $diff_day = Carbon::now()->diffInDays($this->advertisement_expire, false);
        return [
            'id' => $this->id,
            'img' => $this->path,
            'trader' => new TraderResource($trader),
            'link' => $this->link,
            'images' => $this->advertisementImages,
            "daysRemainig" => $diff_day,
        ];
    }
}
