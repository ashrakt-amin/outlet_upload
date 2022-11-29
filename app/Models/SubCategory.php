<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $appends = [
        'sub_category_groups'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class,);
    }

    public function getSubCategoryGroupsAttribute()
    {
        return $this->groups;
    }
}
