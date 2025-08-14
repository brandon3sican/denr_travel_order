<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\TravelOrderRole;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'position_name',
        'assignment_name',
        'div_sec_unit',
        'is_admin',
    ];

    /**
     * Get the travel order roles for the user.
     */
    public function travelOrderRoles()
    {
        return $this->belongsToMany(
            TravelOrderRole::class,
            'user_travel_order_roles',
            'user_email',  // Foreign key on the pivot table
            'travel_order_role_id',  // Related key on the pivot table
            'email',  // Local key on the users table
            'id'      // Related key on the roles table
        )->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    /**
     * Get the employee record associated with the user.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'email', 'email');
    }
    
    /**
     * Get the travel orders that this user needs to approve
     */
    /**
     * Get the travel orders created by this user
     */
    public function travelOrders()
    {
        return $this->hasMany(TravelOrder::class, 'employee_email', 'email');
    }
    
    /**
     * Get the travel orders that this user needs to approve
     */
    public function travelOrdersToApprove()
    {
        return $this->hasMany(TravelOrder::class, 'approver', 'email');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
