<?php

namespace App\Http\Resources;

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
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'famous'      => $this->famous == 0 ? false : true,
            'space'        => $this->space,
            'price_m'      => $this->price_m,
            'unit_value'   => $this->unit_value,
            'deposit'      => $this->deposit,
            'discount'     => $this->discount,
            'rents_count'  => $this->rents_count,
            'description'  => $this->description,
            'images'       => UnitImageResource::collection($this->unitImages),
            'level'        => new LevelResource($this->whenLoaded('level')),
            'level_id'     => $this->level_id,
            'project_id'   => $this->level->project_id,
            'statu'        => new StatuResource($this->unit_statu),
            'site'         => new SiteResource($this->site),
            'trader'       => [
                'id' => $this->trader->id,
                'name' => $this->trader->f_name. ' '. $this->trader->l_name,
                'phone' => $this->trader->phone
            ],
            'items'        => ItemResource::collection($this->unit_items),
            'categories'   => $this->unitCategories,
        ];
    }
}
