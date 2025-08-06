<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TravelOrderRole;

class RoleManagementModel extends Model
{
    protected $table = 'role_management';
    protected $fillable = [
        'user_id',
        'role_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(TravelOrderRole::class);
    }
}
