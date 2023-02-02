<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    const IMAGE_PATH    = 'items';
    protected $appends  = ['path'];
    protected $hidden   = ['created_at', 'updated_at'];
    protected $visible  = [];
    protected $fillable = ['item_id', 'img', 'created_by', 'updated_by'];

    /**
     * Relationships
     */

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function getPathAttribute()
    {
        return asset('storage/images/items') . '/' . $this->img;
    }

}
