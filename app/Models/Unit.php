<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\UnitImageResource;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends  = ['unit_categories', 'unit_level', 'unit_statu', 'unit_trader',  'unit_items', 'images'];
    protected $fillable = ['name', 'project_id', 'level_id', 'trader_id', 'description', 'famous', 'online', 'offers', 'created_by', 'updated_by'];
    protected $hidden   = ['created_at', 'updated_at', 'laravel_through_key', 'pivot'];
    protected $visible  = [ 'id', 'name', 'unit_categories',  'items', 'images'];

    /**
     * Relationships
     */

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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_unit');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * @GETTER & SETTER
     */

    public function getUnitLevelAttribute()
    {
        return $this->level;
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
}
