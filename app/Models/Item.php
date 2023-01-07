<?php

namespace App\Models;

use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, TraitsAuthGuardTrait;

    protected $fillable  = [
        'name',
        'category_id',
        'sale_price',
        'buy_price',
        'buy_discount',
        'unit_id',
        'item_unit_id',
        'item_code',
        'unit_parts_count',
        'available',
        'discount',
        'description',
        'manufactory_id', // 'الشركة المنتجة'
        'agent_id', // 'الشركة الوكيلة'
        'company_id', // 'الشركة الموزعة'
        'importer_id',
        ];

    protected $appends = [
        'wishlist',
        'client_rate',
        'all_rates',
        'client_views',
        'all_views',
        'item_colors',
        'item_sizes',
        // 'item_images',
        'item_category',
        'item_unit',
        'item_stocks'
    ];

    protected $hidden = [
        'type_id',
        'item_unit_id',
        'manufactory_id', // 'الشركة المنتجة'
        'agent_id', // 'الشركة الوكيلة'
        'company_id', // 'الشركة الموزعة'
        'importer_id',
        'colors',
        'sizes',
        'created_at',
        'updated_at'
    ];
    protected $visible = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function manufactory()
    {
        return $this->belongsTo(Manufactory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function importer()
    {
        return $this->belongsTo(Importer::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'stocks');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'stocks');
    }

    public function itemImages()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }


    // GETTER/SETTER

    public function getWishlistAttribute()
    {
        if (!auth()->guard()->check()) return false;
        return Wishlist::where(['item_id'=>$this->id, 'client_id'=>$this->getTokenId('client')])->exists() ? true : false;
    }

    public function getItemCategoryAttribute()
    {
        return $this->category;
    }

    public function getClientRateAttribute()
    {
        if (!auth()->guard()->check()) return false;
        $rate = Rate::select('rate_degree')->where(['item_id'=>$this->id, 'client_id'=>$this->getTokenId('client')])->first();
        return $rate ? $rate->rate_degree : false;
    }

    public function getClientViewsAttribute()
    {
        if (!auth()->guard()->check()) return false;
        return View::where(['item_id'=>$this->id, 'client_id'=>$this->getTokenId('client')])->sum('view_count');
    }

    public function getAllViewsAttribute()
    {
        return View::where(['item_id'=>$this->id])->sum('view_count');
    }

    public function getAllRatesAttribute()
    {
        $allRates =  Rate::select('rate_degree')->where(['item_id'=>$this->id])->get();
        return  count($allRates) > 0 ?  intval($allRates->sum('rate_degree')/$allRates->count('rate_degree'), 2) : false;
    }

    public function getItemStocksAttribute()
    {
        return $this->stocks ? $this->stocks  : false;
    }

    public function getItemColorsAttribute()
    {
        return $this->colors ? $this->colors->unique()->all()  : false;
    }

    public function getItemSizesAttribute()
    {
        return $this->sizes ? $this->sizes->unique()->all()  : false;
    }

    public function getItemUnitAttribute()
    {
        return ItemUnit::where(['id'=>$this->item_unit_id])->first();
        return $this->itemUnit;
    }

    // public function getItemImagesAttribute()
    // {
    //     $itemImages = ItemImage::where(['item_id'=>$this->id])->get();
    //     return   $itemImages ? $itemImages : false;
    // }
}
