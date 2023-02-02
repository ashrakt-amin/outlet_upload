<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable  = [
        'item_id',
        'unit_id',
        'over_price',
        'available',
        'color_id',
        'size_id',
        'volume_id',
        'season_id',
        'manufacture_date',
        'expire_date',
        'stock_code',
        'starting_stock',
        'min_quantity',
        'barcode',
        'spare_barcode',
        'stock_discount',
        'discount_start_date',
        'discount_end_date',
        'stock',
        'created_by',
        'updated_by'
        ];

    protected $appends = [
        // 'stock_item',
        // 'stock_trader',
        // 'stock_color',
        // 'stock_size',
        // 'stock_volume',
        // 'stock_season',
        ];

        protected $hidden = [
            'created_at',
            'updated_at'
        ];

        protected $visible = [
        ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function trader()
    {
        return $this->hasOneThrough(
            Trader::class,
            Unit::class,
            'trader_id',
            'id',
            'unit_id',
            'id',
        );
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function volume()
    {
        return $this->belongsTo(Volume::class);
    }
/**
 * Getter && Setter
 */
    // public function getStockItemAttribute()
    // {
    //     return $this->item;
    // }

    // public function getStockTraderAttribute()
    // {
    //     return $this->trader ? $this->trader  : false;
    // }

    // public function getStockColorAttribute()
    // {
    //     return $this->color ? $this->color : false;
    // }

    // public function getStockSizeAttribute()
    // {
    //     return $this->size ? $this->size : false;
    // }

    // public function getStockVolumeAttribute()
    // {
    //     return $this->volume ? $this->volume : false;
    // }

    // public function getStockSeasonAttribute()
    // {
    //     return $this->season ? $this->season : false;
    // }

}
