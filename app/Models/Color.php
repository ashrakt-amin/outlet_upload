<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_at', 'updated_at'];
    protected $appends = [];
    protected $hidden = ['pivot', 'created_at', 'updated_at'];
    protected $visible = [];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
