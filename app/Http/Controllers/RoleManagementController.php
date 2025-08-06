<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TravelOrderRole;
use App\Models\UserTravelOrderRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleManagementController extends Controller
{
    public function index()
    {
        $perPage = 10;
        
        // Get users with their roles and employee details
        $users = User::with(['travelOrderRoles'])
            ->join('employees', 'users.email', '=', 'employees.email')
            ->select('users.*', 'employees.first_name', 'employees.last_name', 'employees.position')
            ->orderBy('employees.first_name')
            ->orderBy('employees.last_name')
            ->paginate($perPage);
            
        // Get all available roles
        $roles = TravelOrderRole::all();

        return view('role-management.role-management', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
    
    public function updateRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:travel_order_roles,id'
        ]);
        
        // Get the user's email
        $user = User::findOrFail($userId);
        
        // Update or create user role using user_email
        UserTravelOrderRole::updateOrCreate(
            ['user_email' => $user->email],
            [
                'travel_order_role_id' => $request->role_id,
                'user_email' => $user->email
            ]
        );
        
        return back()->with('success', 'User role updated successfully');
    }
}
