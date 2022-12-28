<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [];
    protected $hidden = ['created_at',  'updated_at', 'pivot',];
    protected $visible = [];


    public function traders()
    {
        return $this->belongsToMany(Trader::class, 'activity_trader');
    }
}
