<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $showSignatureAlert = false;
        $userSignature = null;
        
        // Check if user needs to upload a signature
        if (!$user->is_admin && (!$user->employee || !$user->employee->signature)) {
            $showSignatureAlert = true;
        } else {
            $userSignature = $user->employee->signature;
        }
        
        // Base query for travel orders
        $baseQuery = $user->is_admin 
            ? TravelOrder::query()
            : TravelOrder::where('employee_email', $user->email);

        // Apply search filter if provided
        $search = request()->input('search');
        if ($search) {
            $baseQuery->where(function($query) use ($search) {
                $query->where('destination', 'like', "%{$search}%")
                      ->orWhere('purpose', 'like', "%{$search}%")
                      ->orWhereHas('employee', function($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                      });
            });
        }

        // Apply status filter if provided
        $status = request()->input('status');
        
        if ($status) {
            // Get the status ID from the database based on the status name
            $statusId = \App\Models\TravelOrderStatus::where('name', $status)->value('id');
            
            if ($statusId) {
                $baseQuery->where('status_id', $statusId);
            }
        }

        // Get paginated travel orders for the user (5 per page) with employee data
        $travelOrders = (clone $baseQuery)
            ->with(['employee' => function($query) {
                $query->select('id', 'email', 'first_name', 'last_name');
            }])
            ->latest()
            ->paginate(5);

        // Get all travel orders for statistics (without pagination)
        $allTravelOrders = (clone $baseQuery)->get();

        // Calculate statistics
        $totalTravelOrders = $allTravelOrders->count();
        $pendingRequests = $allTravelOrders->where('status_id', 1)->count();
        $completedRequests = $allTravelOrders->whereIn('status_id', [2, 3])->count(); // Assuming 2=Approved, 3=Completed
        $cancelledRequests = $allTravelOrders->where('status_id', 5)->count();

        return view('dashboard', [
            'travelOrders' => $travelOrders,
            'isAdmin' => $user->is_admin,
            'totalTravelOrders' => $totalTravelOrders,
            'pendingRequests' => $pendingRequests,
            'completedRequests' => $completedRequests,
            'cancelledRequests' => $cancelledRequests,
            'showSignatureAlert' => $showSignatureAlert,
            'userSignature' => $userSignature,
        ]);
    }
    public function show($id)
    {
        $travelOrder = TravelOrder::findOrFail($id);
        return view('travel-order.show', [
            'travelOrder' => $travelOrder,
        ]);
    }
}
