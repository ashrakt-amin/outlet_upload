<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitsImageProccessingTrait;


class Trader extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable, TraitsAuthGuardTrait, TraitsImageProccessingTrait;

    const IMAGE_PATH = 'traders';
    protected $appends = ['path'];
    protected $visible = [ 'f_name', 'l_name', 'path', 'phone'];
    protected $fillable = [
        'f_name',
        'l_name',
        'age',
        'img',
        'national_id',
        'phone',
        'email',
        'code',
        'password',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
        'password',
        'remember_token',
        'email_verified_at',
        'phone_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function levels()
    {
        return $this->hasManyThrough(
            Level::class,
            Unit::class,
            'level_id', // Foreign key on the environments table...
            'id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'level_id' // Local key on the environments table..
        );
    }

    public function items()
    {
        // return $this->hasManyThrough(
        //     Item::class,
        //     Unit::class,
        //     'level_id', // Foreign key on the environments table...
        //     'id', // Foreign key on the deployments table...
        //     'id', // Local key on the projects table...
        //     'level_id' // Local key on the environments table..
        // );
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getPathAttribute()
    {
        return asset('storage/images/traders') . '/' . $this->img;
    }

     /**
     * Double Attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    // protected function Img(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => asset('storage/images/traders') . '/' . $this->img,
    //         set: fn ($value) => $this->setImage($value, 'traders', 450, 450),
    //     );
    // }
}
