<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MainProject extends Model
{
    use HasFactory;

    protected $appends  = [];
    protected $fillable = ['name', 'created_by', 'updated_by'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $visible  = [];

    /**
     * Relationships
     */

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
