<?php

namespace App\Http\Resources;

use App\Models\Activity;
use App\Models\Trader;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $level  = $this->whenLoaded('level');
        $trader = $this->whenLoaded('trader');
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'space'        => $this->space,
            'price_m'      => $this->price_m,
            'unit_value'   => $this->unit_value,
            'deposit'      => $this->deposit,
            'discount'     => $this->discount,
            'rents_count'  => $this->rents_count,
            'description'  => $this->description,
            'images'       => UnitImageResource::collection($this->unitImages),
            'level'        => new LevelResource($level),
            'statu'        => new StatuResource($this->unit_statu),
            'site'         => new SiteResource($this->site),
            'trader'       => new TraderResource($trader),
            'items'        => ItemResource::collection($this->unit_items),
            'categories'   => CategoryResource::collection($this->unit_categories),
        ];
    }
}
