<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    use HasFactory;

    protected $appends = [
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'project_id',
    ];

    protected $visible = [
    ];


    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function level()
    {
        return $this->hasMany(Level::class);
    }

    public function traders()
    {
        return $this->hasManyThrough(Trader::class, Unit::class);
    }
}
