<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelImage extends Model
{
    use HasFactory;

    const IMAGE_PATH = 'levels';
    protected $appends = ['path'];
    protected $fillable = ['img', 'level_id', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];

    public function getPathAttribute()
    {
        return asset('storage/images/levels') . '/' . $this->img;
    }
}
