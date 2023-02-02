<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $appends  = [];
    protected $fillable = ['name', 'created_by', 'updated_by'];
    protected $hidden   = ['pivot', 'created_at',  'updated_at'];
    protected $visible  = [];

    /**
     * Relationships
     */

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
