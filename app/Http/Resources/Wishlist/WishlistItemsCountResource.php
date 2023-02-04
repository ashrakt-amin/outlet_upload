<?php

namespace App\Http\Resources\Wishlist;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class WishlistItemsCountResource extends JsonResource
{
    use TraitsAuthGuardTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'count'      => $this->count(),
        ];
    }
}
