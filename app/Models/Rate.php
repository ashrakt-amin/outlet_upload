<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'client_id',
        'rate_degree',
    ];


    public function scopeRateWhereAuth($query, $itemId)
    {

        return $query->where(['client_id' => auth()->guard()->id(), 'item_id' => $itemId]);

    }
}
