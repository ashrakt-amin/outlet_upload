<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $appends = [
        'unit_activities',
        'unit_level',
        'unit_statu',
        'unit_trader',
        'unit_items',
        'unit_images_appended',
    ];

    protected $fillable = [
        'name',
        'level_id',
        'site_id',
        'statu_id',
        'trader_id',
        'finance_id',
        'space',
        'price_m',
        'unit_value',
        'deposit',
        'rent_value',
        'rents_count',
        'discount',
        'description',
    ];

    public function unitImages()
    {
        return $this->hasMany(UnitImage::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function statu()
    {
        return $this->belongsTo(Statu::class)->withDefault(
            [
                'id' => 0,
                'name'=> 'خالية'
            ]);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_trader');
    }

    /**
     * @GETTER & SETTER
     */

    public function getUnitActivitiesAttribute()
    {
        return $this->activities ? $this->activities : false;
    }

    public function getUnitLevelAttribute()
    {
        return $this->level;
    }

    public function getUnitStatuAttribute()
    {
        return $this->statu;
    }

    public function getUnitTraderAttribute()
    {
        return $this->trader ? $this->trader  : false;
    }

    public function getUnitItemsAttribute()
    {
        return $this->trader->items;
    }

    public function getUnitImagesAppendedAttribute()
    {
        return $this->unitImages;
    }
}
