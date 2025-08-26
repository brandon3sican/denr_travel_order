<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'sex',
        'email',
        'emp_status',
        'position_name',
        'assignment_name',
    ];

    /**
     * Get the user that owns the employee record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Get the employee's signature.
     */
    public function signature(): HasOne
    {
        return $this->hasOne(EmployeeSignature::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name . ' ' . $this->suffix;
    }
}
