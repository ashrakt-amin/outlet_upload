<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $traders = $this->whenPivotLoaded('activity_trader', function () {
        //     return $this->pivot->trader;
        // });

        return [
            'id'        => $this->id,
            'name'      => $this->name,
            // 'traders'   => TraderResource::collection($traders),
        ];
    }
}
