<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $appends = [];
    protected $hidden = ['created_at', 'updated_at'];
    protected $visible = [];
    protected $fillable  = ['name', 'created_by', 'updated_by'];

    /**
     * Relationships
     */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function levels()
    {
        return $this->hasMany(Levels::class);
    }
}
