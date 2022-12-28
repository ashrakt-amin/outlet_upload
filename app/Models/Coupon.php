<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $appends = [];
    protected $fillable = [
        'code',
        'client_id',
        'trader_id',
        'item_id',
        'stock_id',
        'percentage_discount',
        'amount_discount',
        'starting_date',
        'expiring_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'updated_at',
    ];
}
