<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class Wishlist extends Model
{
    use HasFactory, TraitsAuthGuardTrait;

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeWishlistWhereAuth($query, $itemId)
    {

        return $query->where(['client_id' => $this->getTokenId('client'), 'item_id' => $itemId]);

    }
}
