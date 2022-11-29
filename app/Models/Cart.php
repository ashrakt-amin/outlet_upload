<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $appends = [
        'item'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];


    protected $fillable  = [
        'color_size_stock_id',
        'trader_id',
        'client_id',
        'quantity',
        ];

    public function item()
    {
        return $this->hasOneThrough(
            Item::class, // cart has one item
            ColorSizeStock::class, // throw ColorSizeStock
            'item_id', // where ColorSizeStock->item_id =
            'id', // item->id
            'color_size_stock_id', // & cart->color_size_stock_id
            'id' // ColorSizeStock->id
        );
    }

    public function colorSizeStock()
    {
        return $this->belongsTo(ColorSizeStock::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }


    public function getItemAttribute()
    {
        return Item::where(['id'=>$this->colorSizeStock->item_id])->first();
    }

    public function scopeWhereAuth($query, $authUser)
    {

        return $query->where($authUser, auth()->guard()->id());

    }
}
