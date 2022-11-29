<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $appends = [
        'type_items'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];

    protected $fillable = [
        'name',
        'group_id',
    ];


    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getTypeItemsAttribute()
    {
        return $this->items;
    }

}
