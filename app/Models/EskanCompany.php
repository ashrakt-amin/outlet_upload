<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EskanCompany extends Model
{
    use HasFactory;

    protected $appends = [
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $visible = [
    ];


    protected $guarded = [];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
