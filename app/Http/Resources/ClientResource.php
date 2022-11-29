<?php

namespace App\Http\Resources;

use App\Models\Wishlist;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $carts            = $this->whenLoaded('carts');
        $wishlists        = $this->whenPivotLoaded(new Wishlist , function () {
            return $this->pivot->item_id;
        });
        return [
            'id'           => $this->id,
            'f_name'       => $this->f_name,
            'm_name'       => $this->m_name,
            'l_name'       => $this->l_name,
            'age'          => $this->age,
            'phone'        => $this->phone,
            'phone2'       => $this->phone2,
            'email'        => $this->email,
            'carts'        => CartResource::collection($carts),
            // 'wishlistItems' => ItemResource::collection($this->wishlistItems),
            // 'wishlists1'     => $wishlists ? $wishlists : 'لا يوجد منتجات في المفضلة',
            'wishlists'     => ItemResource::collection($this->wishlists),
        ];
    }
}
