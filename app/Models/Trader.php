<?php

namespace App\Models;

use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Trader extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable, TraitsAuthGuardTrait;

    const IMAGE_PATH = 'traders';
    protected $appends = ['path', 'trader_items'];
    protected $visible = [ 'f_name', 'l_name', 'img', 'phone'];
    protected $fillable = [
        'f_name',
        'l_name',
        'age',
        'img',
        'national_id',
        'phone',
        'phone2',
        'phone3',
        'phone4',
        'phone5',
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
        'phone2',
        'phone3',
        'phone4',
        'phone5',
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

    public function getPathAttribute()
    {
        return asset('storage/images/traders') . '/' . $this->img;
    }

    public function getTraderItemsAttribute()
    {
        return Item::where(['trader_id'=>$this->id])->get();
        // return $this->items ? $this->items : false;
    }
}
