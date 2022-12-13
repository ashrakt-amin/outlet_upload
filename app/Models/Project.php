<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $appends = [
        'project_levels',
        ];

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at'
    ];

    protected $visible = [
        'id',
        'name',
        'levels'
    ];


    protected $fillable = [
        'name',
    ];

    public function eskanCompany()
    {
        return $this->belongsTo(EskanCompany::class);
    }

    public function units()
    {
        return $this->hasManyThrough(Unit::class, Level::class);
    }

    public function levels()
    {
        return $this->hasMany(Level::class);
        // return $this->hasManyThrough(
        //     Level::class,
        //     Unit::class,
        //     'level_id', // Foreign key on the incoming table...
        //     'id', // Foreign key on the throwing table...
        //     'id', // Local key on the this table...
        //     'level_id', // Local key on the incoming table..
        // );
    }

    public function traders()
    {
        return $this->hasManyThrough(Trader::class, Unit::class);
    }

    public function getProjectLevelsAttribute()
    {
        return Level::where(['project_id'=>$this->id, 'level_type'=> 1 ])->get();
        // return $this->levels ? $this->levels  : false;
    }

}
