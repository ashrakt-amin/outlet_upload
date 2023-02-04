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
            'visitor_id' => (float)$this->visitor_id,
            'count'      => $this->where(function($q) {
                request()->bearerToken() ? $q
                ->where(['client_id' => $this->getTokenId('client')])->where(['visitor_id' => null]) : $q
                ->where(['visitor_id' => $this->visitor_id]);
                })->count(),
        ];
    }
}
