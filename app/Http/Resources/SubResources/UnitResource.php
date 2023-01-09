<?php

namespace App\Http\Resources\SubResources;

use App\Models\Unit;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    // /**
    //  * @var Unit
    //  */
    //  protected $model;

    // /**
    //  * BaseRepository constructor.
    //  *
    //  * @param Unit $unit
    //  */
    // public function __construct(Unit $unit)
    // {
    //     $this->model = Unit::find($unit->id);
    // }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'items'            => ($this->itemOffers),
        ];
    }
}
