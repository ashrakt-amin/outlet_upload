<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $appends = ['path'];

    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $visible = [];


    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function advertisementImages()
    {
        return $this->hasMany(AdvertisementImage::class);
    }

    public function getPathAttribute()
    {
        return asset('storage/images/advertisements') . '/' . $this->img;
    }

}
