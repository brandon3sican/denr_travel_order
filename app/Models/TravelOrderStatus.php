<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelOrderStatus extends Model
{
    protected $table = 'travel_order_status';
    
    protected $fillable = [
        'name'
    ];
    
    public $timestamps = true;

    /**
     * Get the travel orders associated with this status.
     */
    public function travelOrders(): HasMany
    {
        return $this->hasMany(TravelOrder::class, 'status_id');
    }
}
