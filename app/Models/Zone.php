<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $appends = [
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $visible = [
    ];

    protected $fillable  = [
        'name',
        ];

    public function levels()
    {
        return $this->hasMany(Levels::class);
    }
}
