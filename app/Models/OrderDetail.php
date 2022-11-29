<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $appends = [
        'item',
        'next_order_statu'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];



    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function colorSizeStock()
    {
        return $this->belongsTo(ColorSizeStock::class);
    }


    public function orderStatu()
    {
        return $this->belongsTo(OrderStatu::class)->withDefault(
            [
                'id' => 0,
                'name'=> 'اوردر ملغي',
                'code' => 1,
            ]);
    }

    public function getItemAttribute()
    {
        return Item::where(['id'=>$this->colorSizeStock->item_id])->first();
    }


    public function getNextOrderStatuAttribute()
    {
        return OrderStatu::where('id', '>', $this->order_statu_id)->first();
    }

    public function scopeWhereTraderAuth($query, $authUser)
    {

        return $query->where($authUser, auth()->guard()->id());

    }
}
