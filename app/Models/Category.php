<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'parent_id', 'created_by', 'updated_by'];
    protected $appends  = ['parent_category', 'category_sub_categories', 'category_items'];
    protected $hidden   = [];
    protected $visible  = ['id', 'name', 'parent_id',];

    /**
     * Relationships
     */

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'category_unit');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'category_unit');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * getter
     */

    public function getParentCategoryAttribute()
    {
        $parentCategory = Category::where(['id'=>$this->parent_id])->first();
        return $parentCategory;
    }

    public function getCategorySubCategoriesAttribute()
    {
        $subCategories = Category::where(['parent_id'=>$this->id])->get();
        return $subCategories;
    }

    public function getCategoryItemsAttribute()
    {
        return $this->items ? $this->items :false;
    }

    /**
    * Item Offers Attribute.
    *
    * @return Attribute
    */
    // protected function withItemsWhere(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value, $attributes) =>
    //     );
    // }
}
