<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory;

    const IMAGE_PATH = 'advertisements';
    protected $appends = ['path'];
    protected $fillable = [
        'img',
        'link',
        'project_id',
        'unit_id',
        'renew',
        'grade',
        'advertisement_expire',
        'created_by',
        'updated_by'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $visible = [];

    /**
     * Relationships
     */

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getPathAttribute()
    {
        return asset('storage/images/advertisements') . '/' . $this->img;
    }

    /**
    * advertisement Renew Attribute.
    *
    * @return Attribute
    */
    protected function renew(): Attribute
    {
        return Attribute::make(
            // set: fn ($value) => $value > 0 ? $value : $value = 1,
            get: fn ($value) => $value
        );
    }

    /**
    * advertisement Expire Attribute.
    *
    * @return Attribute
    */
    protected function advertisementExpire(): Attribute
    {
        return Attribute::make(
            // set: fn ($value, $attributes) => Carbon::parse(Carbon::now())->addDays($attributes['renew'] * 30),
            get: fn ($value) => $value,
        );
    }
}
