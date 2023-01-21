<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\UnitImageResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $appends = [
        'unit_categories',
        'unit_level',
        'unit_statu',
        'unit_trader',
        'unit_items',
        'images',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        // 'laravel_through_key',
        'pivot',
    ];

    protected $visible = [
        'id',
        'name',
        'unit_categories',
        'items',
        'images',
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
        'famous',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

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

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_unit');
    }

    /**
     * @GETTER & SETTER
     */

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
        return $this->items;
    }

    public function getImagesAttribute()
    {
        return UnitImageResource::collection($this->unitImages);
    }

    /**
    * Item Offers Attribute.
    *
    * @return \Illuminate\Database\Eloquent\Casts\Attribute
    */
   protected function itemOffers(): Attribute
   {
       return Attribute::make(
           get: fn ($value) => item::where(['unit_id'=> $this->id])->where('discount', '>', 0)->get(),
       );
   }

    /**
    * Item Offers Attribute.
    *
    * @return \Illuminate\Database\Eloquent\Casts\Attribute
    */
   protected function unitCategories(): Attribute
   {
       return Attribute::make(
           get: fn ($value) => $this->categories,
       );
   }

    /**
    * Item Offers Attribute.
    *
    * @return Attribute
    */
   protected function unitValue(): Attribute
   {
        return Attribute::make(
            set: fn ($value, $attributes) => $attributes['unit_value'] = $attributes['price_m'] * $attributes[ 'space'],
        );
   }

    /**
    * Item Offers Attribute.
    *
    * @return Attribute
    */
   protected function rentValue(): Attribute
   {
        return Attribute::make(
            set: fn ($value, $attributes) => $attributes['rent_value'] = $attributes['price_m'] * $attributes[ 'space'] / 36 ,
        );
   }
}
