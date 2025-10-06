<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status_id',
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

    public function travelOrderNumber()
    {
        return $this->hasOne(TravelOrderNumber::class);
    }

    /**
     * Get the employee's signature for this travel order.
     */
    public function employeeSignature()
    {
        return $this->hasOneThrough(
            EmployeeSignature::class,
            Employee::class,
            'email', // Foreign key on employees table...
            'employee_id', // Foreign key on employee_signatures table...
            'employee_email', // Local key on travel_orders table...
            'id' // Local key on employees table...
        );
    }

    /**
     * Get the recommender's signature for this travel order.
     */
    public function recommenderSignature()
    {
        return $this->hasOneThrough(
            EmployeeSignature::class,
            Employee::class,
            'email', // Foreign key on employees table...
            'employee_id', // Foreign key on employee_signatures table...
            'recommender', // Local key on travel_orders table...
            'id' // Local key on employees table...
        );
    }

    /**
     * Get the approver's signature for this travel order.
     */
    public function approverSignature()
    {
        return $this->hasOneThrough(
            EmployeeSignature::class,
            Employee::class,
            'email', // Foreign key on employees table...
            'employee_id', // Foreign key on employee_signatures table...
            'approver', // Local key on travel_orders table...
            'id' // Local key on employees table...
        );
    }

    /**
     * Get all status histories for this travel order.
     */
    public function statusHistories()
    {
        return $this->hasMany(TravelOrderStatusHistory::class, 'travel_order_id')->latest();
    }

    /**
     * Get the latest status update for this travel order.
     */
    public function latestStatusUpdate()
    {
        return $this->hasOne(TravelOrderStatusHistory::class, 'travel_order_id')->latest();
    }
}
