<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function finance()
    {
        return $this->belongsTo(Finance::class);
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

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function scopeWhereAuth($query, $authUser)
    {

        return $query->where($authUser, auth()->guard()->id());

    }

}
