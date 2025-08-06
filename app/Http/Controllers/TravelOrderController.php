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
        return view('travel-order.index', [
            'travelOrders' => TravelOrderModel::all(),
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

        // Set the current user as the creator
        $user = Auth::user();
        $validated['created_by'] = $user->id;
        $validated['updated_by'] = $user->id;

        // Create the travel order
        $travelOrder = TravelOrderModel::create($validated);

        // Send notification to recommender
        // You can implement this later using Laravel Notifications
        // Notification::send($recommender, new TravelOrderForRecommendation($travelOrder));

        return redirect()->route('travel-orders.index')
            ->with('success', 'Travel Order has been submitted for recommendation.');
    }

    public function show($id)
    {
        return view('travel-order.show', [
            'travelOrder' => TravelOrderModel::findOrFail($id),
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
