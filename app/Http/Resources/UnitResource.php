<?php

namespace App\Http\Resources;

use App\Models\Activity;
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
        $activities       = $this->whenPivotLoaded('activity_trader', function () {
            return $this->pivot->activity;
        });
        // $acti = $this->activities->pluck('pivot.activity_id')->unique()->all();
        // $acti = $this->activities->pluck('pivot.activity_id')->unique()->join(' ');
        $construction = $this->whenLoaded('construction');
        $level        = $this->whenLoaded('level');
        $statu        = $this->whenLoaded('statu');
        $site         = $this->whenLoaded('site');
        $trader       = $this->whenLoaded('trader');
        $finance      = $this->whenLoaded('finance');
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
            'construction' => new ConstructionResource($construction),
            'level'        => new LevelResource($level),
            'statu'        => new StatuResource($statu),
            'site'         => new SiteResource($site),
            'trader'       => new TraderResource($trader),
            'activities'   => ActivityResource::collection($activities),
            // 'activities'   => $this->relationLoaded('activities') ? $acti : null
        ];
    }
}
