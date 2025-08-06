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
        
        // Base query for travel orders
        $baseQuery = $user->is_admin 
            ? TravelOrder::query()
            : TravelOrder::where('employee_email', $user->email);

        // Get all travel orders for the user
        $travelOrders = (clone $baseQuery)->with('employee')->latest()->get();

        // Calculate statistics
        $totalTravelOrders = $travelOrders->count();
        $pendingRequests = $travelOrders->where('status_id', 1)->count();
        $completedRequests = $travelOrders->whereIn('status_id', [2, 3])->count(); // Assuming 2=Approved, 3=Completed

        return view('dashboard', [
            'travelOrders' => $travelOrders,
            'isAdmin' => $user->is_admin,
            'totalTravelOrders' => $totalTravelOrders,
            'pendingRequests' => $pendingRequests,
            'completedRequests' => $completedRequests
        ]);
    }
}
