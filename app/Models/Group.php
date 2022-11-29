<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $appends = [
        'group_types'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];

    protected $fillable = [
        'name',
        'sub_category_id',
    ];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function types()
    {
        return $this->hasMany(Type::class);
    }

    public function getGroupTypesAttribute()
    {
        return $this->types;
    }
}
