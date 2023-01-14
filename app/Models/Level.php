<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\LevelImageResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory;

    protected $appends = ['level_units', 'images'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'project_id',
        'laravel_through_key',
        'pivot',
    ];

    protected $visible = [
        'id',
        'name',
        'level_units',
        'images',
        'units',
    ];

    protected $fillable = [
        'name',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function levelImages()
    {
        return $this->hasMany(LevelImage::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function traders()
    {
        return $this->hasManyThrough(
            trader::class,
            Unit::class,
            'trader_id', // Foreign key on the relation table...(unit.trader_id)
            'id', // Local key on the this table...(trader.id)
            'id', // Local key on the incoming table..()
            'trader_id', // Foreign key on the throwing table...(unit.trader_id)
        );
    }

    /**
    * Level Units Attribute.
    *
    * @return Attribute
    */
    protected function levelUnits(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Unit::where(['level_id'=>$this->id])->inRandomOrder()->limit(10)->get(),
        );
    }
    /**
     * get attributes
     */
    // public function getLevelUnitsAttribute()
    // {
    //     return Unit::where(['level_id'=>$this->id])->inRandomOrder()->limit(10)->get();
    // }

    /**
     * get attributes
     */
    public function getImagesAttribute()
    {
        return LevelImageResource::collection($this->levelImages);
    }
}

