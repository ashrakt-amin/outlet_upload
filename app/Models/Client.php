<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;


class Client extends  Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TraitsAuthGuardTrait, SoftDeletes;


    protected $guard = 'client';
    protected $fillable = [
        'f_name',
        'l_name',
        'age',
        'phone',
        'phone2',
        'email',
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

    /**
     * Relationships
     */

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists()
    {
        return $this->belongsToMany(Item::class, 'wishlists');
    }

    public function rateItems()
    {
        return $this->belongsToMany(Item::class, 'rates');
    }

    public function viewItems()
    {
        return $this->belongsToMany(Item::class, 'views');
    }

    public function view()
    {
        return $this->belongsToMany(Wishlist::class, 'views')->withTimestamps();
    }

    public function rates()
    {
        return $this->belongsToMany(Rate::class, 'rates')->withTimestamps();
    }

    // public function wishlists()
    // {
    //     return $this->belongsToMany(Item::class, 'wishlists')->withTimestamps();
    // }

    public function hisWishlists($itemId)
    {
        return self::wishlists()->where('item_id', $itemId)->exists();
    }


    public function scopeWhereClientAuth($query, $authUser)
    {

        return $query->where($authUser, $this->getTokenId('client'));

    }
}
