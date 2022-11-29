<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = [
        'category_sub_categories'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];

    protected $guarded = [];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function groups()
    {
        return $this->hasManyThrow(Group::class, SubCategory::class);
    }

    public function getCategorySubCategoriesAttribute()
    {
        return $this->subCategories;
    }
}
