<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\UnitResource;
use Illuminate\Database\Eloquent\Model;
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
    ];

    protected $fillable = [
        'name',
        'project_id',
        'level_type',
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

    public function getLevelUnitsAttribute()
    {
        // return $this->units;
        return Unit::where(['level_id'=>$this->id])->inRandomOrder()->limit(10)->get();
    }

    // public function getActivitiesAttribute()
    // {
    //     $units = $this->units;
    //     foreach ($units as $unit) {
    //         $pivot = DB::table('activity_trader')->where(['unit_id'=>$unit->id])->first();
    //         $array = [];
    //     }
    // }
}

