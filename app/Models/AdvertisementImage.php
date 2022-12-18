<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementImage extends Model
{
    use HasFactory;

    protected $appends = [
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];

    protected $fillable  = [
        'advertisement_id',
        'img',
        ];

    public function advertisement()
    {
        return $this->hasOne(Advertisement::class);
    }
}
