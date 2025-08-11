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
        'employee_salary',
        'destination',
        'purpose',
        'departure_date',
        'arrival_date',
        'appropriation',
        'per_diem',
        'laborer_assistant',
        'remarks',
        'recommender',
        'approver',
        'status_id'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_email', 'email');
    }

    public function recommenderEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'recommender', 'email');
    }

    public function approverEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approver', 'email');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TravelOrderStatus::class, 'status_id');
    }
}
