<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $appends  = ['project_levels'];
    protected $fillable = ['name', 'main_project_id', 'created_by', 'updated_by'];
    protected $hidden   = ['pivot', 'created_at', 'updated_at'];
    protected $visible  = ['id', 'name', 'levels', 'units', ];

    /**
     * Relationships
     */

    public function mainProject()
    {
        return $this->belongsTo(MainProject::class);
    }

    public function projectImages()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function units()
    {
        return $this->hasManyThrough(
            Unit::class,
            Level::class,
            'project_id', // Foreign key on the projects table...
            'level_id', // Foreign key on the units table...
            'id', // Local key on the projects  table...
            'id' // Local key on the levele table...
        );
    }

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    public function traders()
    {
        return $this->hasManyThrough(Trader::class, Unit::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_unit');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * getters
     */

    public function getProjectLevelsAttribute()
    {
        return $this->levels;
    }

}
