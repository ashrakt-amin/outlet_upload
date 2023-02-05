<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ProjectImage extends Model
{
    use HasFactory;
    use TraitImageProccessingTrait;

    const IMAGE_PATH    = 'projects';
    protected $appends  = ['path'];
    protected $fillable = ['img', 'project_id', 'created_by', 'updated_by'];
    protected $hidden   = ['created_at', 'updated_at'];

    /**
     * Relationships
     */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Attribute
     */

    /**
    * Project Image Path Attribute.
    *
    * @return Attribute
    */
    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('storage/images/projects') . '/' . $this->img,
        );
    }

    /**
    * Project Image Path Attribute.
    *
    * @return Attribute
    */
    protected function createdBy(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value, $attributes) => dd($attributes['created_by']),
        );
    }

}
