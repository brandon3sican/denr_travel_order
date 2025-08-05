<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelOrderRole extends Model
{
    protected $table = 'travel_order_roles';

    protected $fillable = [
        'name',
        'description',
    ];

    public function userTravelOrderRoles(): HasMany
    {
        return $this->hasMany(UserTravelOrderRole::class);
    }
}
