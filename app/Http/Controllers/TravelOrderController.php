<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelOrder as TravelOrderModel;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrderRole;
use App\Models\Employee;
use App\Models\EmployeeSignature;
use App\Models\TravelOrderStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TravelOrderController extends Controller
{
    /**
     * Get travel order details for AJAX requests
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails($id)
    {
        try {
            $travelOrder = TravelOrderModel::with([
                'employee',
                'employee.signature',
                'status',
                'recommenderEmployee',
                'approverEmployee'
            ])->findOrFail($id);

            // Add signature URL if it exists
            if ($travelOrder->employee && $travelOrder->employee->signature) {
                $travelOrder->employee->signature_url = asset('storage/' . $travelOrder->employee->signature->signature_path);
            }

            return response()->json([
                'success' => true,
                'data' => $travelOrder
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch travel order details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Check if user has a signature
        if (!$user->employee || !$user->employee->signature) {
            return redirect()->route('signature.index')
                ->with('error', 'Please upload your signature before creating a travel order.');
        }
        
        // Get the employee record for the current user
        $employee = $user->employee;
        
        if (!$employee) {
            return redirect()->route('dashboard')
                ->with('error', 'Employee record not found. Please contact the administrator.');
        }
        
        // Get the list of employees who can recommend (travel_order_role_id 3 or 5)
        $recommenders = Employee::where('id', '!=', $employee->id)
            ->whereHas('user.travelOrderRoles', function($query) {
                $query->whereIn('travel_order_role_id', [3, 5]);
            })
            ->with('user')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
            
        // Get the list of employees who can approve (travel_order_role_id 4 or 5)
        $approvers = Employee::where('id', '!=', $employee->id)
            ->whereHas('user.travelOrderRoles', function($query) {
                $query->whereIn('travel_order_role_id', [4, 5]);
            })
            ->with('user')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
            
        return view('travel-order.create-travel-order', compact('employee', 'recommenders', 'approvers'));
    }

    public function store(Request $request)
    {
        // Check if user has a signature
        $user = Auth::user();
        if (!$user->employee || !$user->employee->signature) {
            return redirect()->route('signature.index')
                ->with('error', 'Please upload your signature before creating a travel order.');
        }

        $validated = $request->validate([
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
            'recommender' => 'required|email',
            'approver' => 'required|email',
            'status_id' => 'required',
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
            'employee.signature',
            'recommenderEmployee.signature',
            'approverEmployee.signature',
            'status'
        ])->findOrFail($id);

        // Helper function to add signature URL
        $addSignatureUrl = function ($employee) {
            if ($employee && $employee->signature) {
                $signaturePath = $employee->signature->signature_path;
                $employee->signature->signature_url = asset('storage/' . ltrim($signaturePath, '/'));
                
                // Log the signature URL for debugging
                Log::info('Signature URL:', [
                    'employee_id' => $employee->id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'signature_path' => $signaturePath,
                    'full_url' => $employee->signature->signature_url
                ]);
            }
            return $employee;
        };

        // Add signature URLs for all relevant employees
        if ($travelOrder->employee) {
            $travelOrder->employee = $addSignatureUrl($travelOrder->employee);
        }
        if ($travelOrder->recommenderEmployee) {
            $travelOrder->recommenderEmployee = $addSignatureUrl($travelOrder->recommenderEmployee);
        }
        if ($travelOrder->approverEmployee) {
            $travelOrder->approverEmployee = $addSignatureUrl($travelOrder->approverEmployee);
        }

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
