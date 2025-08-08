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
        'destination',
        'purpose',
        'departure_date',
        'arrival_date',
        'appropriation',
        'per_diem',
        'employee_salary',
        'laborer_assistant',
        'remarks',
        'recommender',
        'approver',
        'status_id',
        'created_by',
        'updated_by'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_email', 'email');
    }
}
