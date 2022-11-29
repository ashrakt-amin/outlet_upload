<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorSizeStock extends Model
{
    use HasFactory;

    protected $fillable  = [
        'item_id',
        'trader_id',
        'buy_price',
        'sale_price',
        'color_id',
        'size_id',
        'weight_id',
        'volume_id',
        'stock'
        ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
