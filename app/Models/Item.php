<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ItemUnitResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $appends = [
        'wishlist',
        'client_rate',
        'all_rates',
        'client_views',
        'all_views',
        'item_trader',
        'item_colors',
        'item_sizes',
        'item_images',
        'item_type',
        'item_unit',
        'item_color_size_stocks',
        'item_stocks'
        ];

        protected $hidden = [
            'type_id',
            'item_unit_id',
            'item_code',
            'season_id',
            'weight_id',
            'volume_id',
            'manufactory_id', // 'الشركة المنتجة'
            'agent_id', // 'الشركة الوكيلة'
            'company_id', // 'الشركة الموزعة'
            'importer_id',
            'offer_id',
            'colors',
            'sizes',
            'created_at',
            'updated_at'
        ];

        protected $visible = [
        ];


    protected $fillable  = [
        'name',
        'type_id',
        'trader_id',
        'item_unit_id',
        'unit_parts_count',
        'available',
        'description',
        'manufactory_id', // 'الشركة المنتجة'
        'agent_id', // 'الشركة الوكيلة'
        'company_id', // 'الشركة الموزعة'
        'import', // ->boolean
        'importer_id',
        ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
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
        return $this->belongsToMany(Color::class, 'color_size_stocks');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'color_size_stocks');
    }

    public function itemImages()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class);
    }

    public function colorSizeStocks()
    {
        return $this->hasMany(ColorSizeStock::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }


    // GETTER/SETTER

    public function getItemTraderAttribute()
    {
        return $this->trader;
    }

    public function getWishlistAttribute()
    {
        if (!auth()->guard()->check()) return false;
        return Wishlist::where(['item_id'=>$this->id, 'client_id'=>auth()->guard()->id()])->exists();
    }

    public function getClientRateAttribute()
    {
        if (!auth()->guard()->check()) return false;
        $rate = Rate::select('rate_degree')->where(['item_id'=>$this->id, 'client_id'=>auth()->guard()->id()])->first();
        return $rate ? $rate->rate_degree : false;
    }

    public function getClientViewsAttribute()
    {
        if (!auth()->guard()->check()) return false;
        return View::where(['item_id'=>$this->id, 'client_id'=>auth()->guard()->id()])->sum('view_count');
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

    public function getItemColorSizeStocksAttribute()
    {
        return $this->colorSizeStocks ? $this->colorSizeStocks  : false;
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

    public function getItemImagesAttribute()
    {
        $itemImages = ItemImage::where(['item_id'=>$this->id])->get();
        return   $itemImages ? $itemImages : false;
    }

    public function getItemTypeAttribute()
    {
        $type = Type::where(['id'=>$this->type_id])->first();
        return [
            'id'  =>$type->id,
            'name'=>$type->name,
        ];
    }
}
