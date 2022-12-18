<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $appends = [
    ];

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];


    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }

    public function advertisementImages()
    {
        return $this->hasMany(AdvertisementImage::class);
    }

}
