<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // public function getClient():Attribute {
    //     return new Attribute{
    //         get: fn($client)=>Wishlist::where(['client_id'=>auth()->guard()->id())->first(),
    //     }
    // }

    public function scopeWishlistWhereAuth($query, $itemId)
    {

        return $query->where(['client_id' => auth()->guard()->id(), 'item_id' => $itemId]);

    }
}
