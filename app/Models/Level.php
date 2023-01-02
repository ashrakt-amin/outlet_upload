<?php

namespace App\Models;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UnitResource;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ActivityResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory;

    protected $appends = ['level_units'];

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
        'activity',
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
            'trader_id', // Foreign key on the incoming table...
            'id', // Foreign key on the throwing table...
            'id', // Local key on the this table...
            'trader_id' // Local key on the incoming table..
        );
    }

    /**
     * get attributes
     */
    public function getLevelUnitsAttribute()
    {
        return Unit::inRandomOrder()->limit(10)->get();
    }
}

