<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitImage extends Model
{

    use HasFactory;

    const IMAGE_PATH = 'units';
    protected $appends = ['path'];
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    public function getPathAttribute()
    {
        return asset('storage/images/units') . '/' . $this->img;
    }

}
