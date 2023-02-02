<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    const IMAGE_PATH = 'projects';
    
    protected $appends = ['path'];

    protected $fillable = ['img', 'project_id', 'created_at', 'updated_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getPathAttribute()
    {
        return asset('storage/images/projects') . '/' . $this->img;
    }

}
