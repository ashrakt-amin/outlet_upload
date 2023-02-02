<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelImage extends Model
{
    use HasFactory;

    const IMAGE_PATH = 'levels';
    protected $appends = ['path'];
    protected $fillable = ['img', 'level_id', 'created_by', 'updated_by'];
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Relationships
     */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * @Getter & Setter Attribute
     */

    public function getPathAttribute()
    {
        return asset('storage/images/levels') . '/' . $this->img;
    }
}
