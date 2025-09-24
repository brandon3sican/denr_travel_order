<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelOrderStatusHistory extends Model
{
    protected $fillable = [
        'travel_order_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'location',
        'client_meta',
    ];

    protected $casts = [
        'location' => 'array',
        'client_meta' => 'array',
    ];

    public function travelOrder(): BelongsTo
    {
        return $this->belongsTo(TravelOrder::class, 'travel_order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
