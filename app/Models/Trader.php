<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Trader extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = [
        // 'trader_activities',
        // 'trader_levels'
        ];

    protected $guard = 'trader';

    protected $fillable = [
        'f_name',
        'm_name',
        'l_name',
        'age',
        'national_id',
        'phone',
        'phone2',
        'email',
        'code',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_trader');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * getter && setter
     */
    // public function getTraderActivitiesAttribute()
    // {
    //     return $this->activities;
    // }

    // public function getTraderLevelsAttribute()
    // {
    //     return $this->levels;
    // }

}
