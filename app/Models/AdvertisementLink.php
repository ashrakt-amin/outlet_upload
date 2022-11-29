<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function adverisment()
    {
        return $this->belongsTo(Advertisement::class);
    }

}
