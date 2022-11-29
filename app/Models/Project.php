<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function constructions()
    {
        return $this->hasMany(Construction::class);
    }

    public function units()
    {
        return $this->hasManyThrough(Unit::class, Construction::class);
    }

    public function levels()
    {
        return $this->hasManyThrough(Level::class, Construction::class);
    }

}
