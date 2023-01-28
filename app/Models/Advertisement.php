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
        'advertisement_expire'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $visible = [];

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
    * advertisement Expire Attribute.
    *
    * @return Attribute
    */
    protected function advertisementExpire(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
            // dd($this->renew()),
                // $this->renew() && $this->renew() == 0
                // ? $this->delete()
                // :
                Carbon::parse($value)->diffForHumans(),
        );
    }

    /**
    * advertisement Renew Attribute.
    *
    * @return Attribute
    */
    protected function renew(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
            // ($this->advertisementExpire()),
                $this->advertisementExpire > date('Y-m-d') && $value > 0
                ? $this->update([$value = $value - 1])
                : $value,
        );
    }
}
