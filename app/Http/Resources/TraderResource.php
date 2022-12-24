<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\OrderDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class TraderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $units         = $this->whenLoaded('units');
        $levels        = $this->whenLoaded('levels');
        $items         = $this->whenLoaded('items');
        $orderDetails  = $this->whenLoaded('orderDetails');
        return [
            'id'           => $this->id,
            'f_name'       => $this->f_name,
            'm_name'       => $this->m_name,
            'l_name'       => $this->l_name,
            'img'          => $this->path,
            'age'          => Carbon::parse($this->age)->age,
            'phone'        => $this->phone,
            'phone2'       => $this->phone2,
            'phone3'       => $this->phone3,
            'phone4'       => $this->phone4,
            'phone5'       => $this->phone5,
            'email'        => $this->email,
            'code'         => $this->code,
            'levels'       => LevelResource::collection($levels),
            'units'        => UnitResource::collection($units),
            'items'        => ItemResource::collection($items),
            // 'orderDetails' => OrderDetailResource::collection($orderDetails),
            // 'activities'   => $this->trader_activities,
        ];
    }
}
