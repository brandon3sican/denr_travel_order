<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelOrderStatus extends Model
{
    protected $table = 'travel_order_status';
    
    protected $fillable = [
        'name'
    ];
    
    public $timestamps = true;
}
