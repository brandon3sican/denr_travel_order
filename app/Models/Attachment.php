<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'travel_order_id',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
    ];

    /**
     * Get the travel order that owns the attachment.
     */
    public function travelOrder(): BelongsTo
    {
        return $this->belongsTo(TravelOrder::class);
    }
}
