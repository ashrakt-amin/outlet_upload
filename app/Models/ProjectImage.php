<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{

    const IMAGE_PATH = 'projects';
    use HasFactory;

    protected $appends = ['path'];

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getPathAttribute()
    {
        return asset('storage/images/projects') . '/' . $this->img;
    }

}
