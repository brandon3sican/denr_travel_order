<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelOrder as TravelOrderModel;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrderRole;

class TravelOrderController extends Controller
{
    public function index()
    {
        $perPage = request()->input('per_page', 10);
        $search = request()->input('search');
        $status = request()->input('status');
        $dateRange = request()->input('date_range');

        $query = TravelOrderModel::select('travel_orders.*')
            ->with(['employee', 'status'])
            ->leftJoin('employees as recommender', 'travel_orders.recommender', '=', 'recommender.email')
            ->leftJoin('employees as approver', 'travel_orders.approver', '=', 'approver.email')
            ->addSelect([
                'recommender.first_name as recommender_first_name',
                'recommender.last_name as recommender_last_name',
                'recommender.position_name as recommender_position',
                'approver.first_name as approver_first_name',
                'approver.last_name as approver_last_name',
                'approver.position_name as approver_position',
            ]);

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('travel_orders.destination', 'like', "%{$search}%")
                  ->orWhere('travel_orders.purpose', 'like', "%{$search}%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply status filter
        if ($status) {
            $query->whereHas('status', function($q) use ($status) {
                $q->where('name', 'like', "%{$status}%");
            });
        }

        // Apply date range filter
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::parse(trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::parse(trim($dates[1]))->endOfDay();
                
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('departure_date', [$startDate, $endDate])
                      ->orWhereBetween('arrival_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('departure_date', '<=', $startDate)
                            ->where('arrival_date', '>=', $endDate);
                      });
                });
            }
        }

        $travelOrders = $query->latest('travel_orders.created_at')
            ->paginate($perPage)
            ->withQueryString();

        $statuses = \App\Models\TravelOrderStatus::all();

        return view('travel-order.all-travel-orders-simple', [
            'travelOrders' => $travelOrders,
            'statuses' => $statuses,
        ]);
    }

    public function create()
    {
        $currentUser = Auth::user();
        
        // Fetch users with recommender or recommender and approver roles, excluding current user
        $recommenders = \App\Models\User::select('users.*', 'employees.first_name', 'employees.last_name', 'employees.position_name as position')
            ->join('employees', 'users.email', '=', 'employees.email')
            ->join('user_travel_order_roles', 'users.email', '=', 'user_travel_order_roles.user_email')
            ->join('travel_order_roles', 'user_travel_order_roles.travel_order_role_id', '=', 'travel_order_roles.id')
            ->whereIn('travel_order_roles.name', ['Recommender', 'Recommender and Approver'])
            ->where('users.email', '!=', $currentUser->email)
            ->distinct()
            ->get();

        // Fetch users with approver or recommender and approver roles, excluding current user
        $approvers = \App\Models\User::select('users.*', 'employees.first_name', 'employees.last_name', 'employees.position_name as position')
            ->join('employees', 'users.email', '=', 'employees.email')
            ->join('user_travel_order_roles', 'users.email', '=', 'user_travel_order_roles.user_email')
            ->join('travel_order_roles', 'user_travel_order_roles.travel_order_role_id', '=', 'travel_order_roles.id')
            ->whereIn('travel_order_roles.name', ['Approver', 'Recommender and Approver'])
            ->where('users.email', '!=', $currentUser->email)
            ->distinct()
            ->get();

        return view('travel-order.create-travel-order', [
            'recommenders' => $recommenders,
            'approvers' => $approvers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_email' => 'required|email|exists:employees,email',
            'employee_salary' => 'required|numeric|min:0',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date',
            'appropriation' => 'required|string|max:255',
            'per_diem' => 'required|numeric|min:0',
            'laborer_assistant' => 'required|numeric|min:0',
            'remarks' => 'required|string',
            'recommender' => 'required|email|exists:users,email',
            'approver' => 'required|email|exists:users,email',
            'status_id' => 'required|exists:travel_order_status,id'
        ]);

        // Create the travel order
        $travelOrder = TravelOrderModel::create($validated);

        // Send notification to recommender
        // You can implement this later using Laravel Notifications
        // Notification::send($recommender, new TravelOrderForRecommendation($travelOrder));

        return redirect()->route('my-travel-orders')
            ->with('success', 'Travel Order has been submitted for recommendation.');
    }

    public function show($id)
    {
        $travelOrder = TravelOrderModel::with([
            'employee',
            'recommenderEmployee',
            'approverEmployee',
            'status'
        ])->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($travelOrder);
        }

        return view('travel-order.show', [
            'travelOrder' => $travelOrder,
        ]);
    }

    public function edit($id)
    {
        return view('travel-order.edit', [
            'travelOrder' => TravelOrderModel::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_email' => 'required|email',
            'employee_salary' => 'required|numeric|min:0',
            'destination' => 'required',
            'purpose' => 'required',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date',
            'appropriation' => 'required',
            'per_diem' => 'required|numeric',
            'laborer_assistant' => 'required|numeric',
            'remarks' => 'required',
            'status_id' => 'required',
        ]);

        $travelOrder = TravelOrderModel::findOrFail($id);
        $travelOrder->update($request->all());

        return redirect()->route('travel-orders.show', $travelOrder->id)->with('success', 'Travel Order updated successfully');
    }

    public function destroy($id)
    {
        $travelOrder = TravelOrderModel::findOrFail($id);
        $travelOrder->delete();

        return redirect()->route('dashboard')->with('success', 'Travel Order deleted successfully');
    }
}
