<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $appends = [
        'level_units'
    ];

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
        return Unit::where(['level_id'=>$this->id])->get();
        return $this->units ? $this->units  : false;
    }
}

