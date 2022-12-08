<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = [
        'parent_category',
        'category_sub_categories',
        'category_items'
    ];

    protected $hidden = [
    ];

    protected $visible = [
        'id',
        'name',
    ];

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function groups()
    {
        return $this->hasManyThrow(Group::class, SubCategory::class);
    }

    public function getParentCategoryAttribute()
    {
        // if ($this->category_id < 1) return false;
        $parentCategory = Category::where(['id'=>$this->category_id])->first();
        return $parentCategory;
    }

    public function getCategorySubCategoriesAttribute()
    {
        // if ($this->category_id < 1) return false;
        $subCategories = Category::where(['category_id'=>$this->id])->get();
        return $subCategories;
    }

    public function getCategoryItemsAttribute()
    {
        // return Item::where(['category_id'=>$this->id])->get();
        return $this->items ? $this->items :false;
    }
}
