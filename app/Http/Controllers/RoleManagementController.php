<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TravelOrderRole;
use App\Models\UserTravelOrderRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleManagementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        
        // Base query
        $query = User::with(['travelOrderRoles'])
            ->join('employees', 'users.email', '=', 'employees.email')
            ->select('users.*', 'employees.first_name', 'employees.last_name', 'employees.position_name as position', 'employees.assignment_name');
            
        // Apply assignment filter if provided
        if ($request->has('assignment') && !empty($request->assignment)) {
            $query->where('employees.assignment_name', $request->assignment);
        }
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('employees.first_name', 'like', $searchTerm)
                  ->orWhere('employees.last_name', 'like', $searchTerm)
                  ->orWhere('employees.email', 'like', $searchTerm)
                  ->orWhere('employees.position_name', 'like', $searchTerm);
            });
        }
        
        // Order and paginate results
        $users = $query->orderBy('employees.first_name')
                      ->orderBy('employees.last_name')
                      ->paginate($perPage)
                      ->withQueryString();
            
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
