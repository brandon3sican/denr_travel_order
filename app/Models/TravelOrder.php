<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Employee;

class TravelOrder extends Model
{
    protected $table = 'travel_orders';

    protected $fillable = [
        'employee_email',
        'purpose',
        'departure_date',
        'arrival_date',
        'appropriation',
        'per_diem',
        'laborer_assistant',
        'remarks',
        'status_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
