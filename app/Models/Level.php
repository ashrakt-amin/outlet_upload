<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $appends = [
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'construction_id',
        'laravel_through_key',
        'pivot',
    ];

    protected $visible = [
    ];

    protected $fillable = [
        'name',
        'construction_id',
    ];

    public function construction()
    {
        return $this->belongsTo(Construction::class);
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
            'trader_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'trader_id' // Local key on the environments table..
        );
    }
}

