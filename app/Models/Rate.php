<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class Rate extends Model
{
    use HasFactory, TraitsAuthGuardTrait;

    protected $fillable = ['item_id', 'client_id', 'rate_degree'];

    /**
     * Scope
     */

    public function scopeRateWhereAuth($query, $itemId)
    {
        return $query->where(['client_id' => $this->getTokenId('client'), 'item_id' => $itemId]);
    }
}
