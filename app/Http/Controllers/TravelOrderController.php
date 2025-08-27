<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSignature;
use App\Models\TravelOrder as TravelOrderModel;
use App\Models\TravelOrderRole;
use App\Models\TravelOrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TravelOrderController extends Controller
{
    /**
     * Get travel order details for AJAX requests
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Display travel orders that need approval
     *
     * @return \Illuminate\View\View
     */
    public function forApproval()
    {
        $user = Auth::user();
        
        $travelOrders = TravelOrderModel::with(['employee', 'status'])
            ->where('approver', $user->email)
            ->whereHas('status', function($query) {
                $query->where('name', 'For Approval');
            })
            ->latest()
            ->paginate(10);
            
        $statuses = TravelOrderStatus::all();
            
        return view('travel-order.for-approval', [
            'travelOrders' => $travelOrders,
            'statuses' => $statuses
        ]);
    }
    
    /**
     * Display travel orders that need recommendation
     *
     * @return \Illuminate\View\View
     */
    public function forRecommendation()
    {
        $user = Auth::user();
        
        $travelOrders = TravelOrderModel::with(['employee', 'status'])
            ->where('recommender', $user->email)
            ->whereHas('status', function($query) {
                $query->where('name', 'For Recommendation');
            })
            ->latest()
            ->paginate(10);
            
        $statuses = TravelOrderStatus::all();
            
        return view('travel-order.for-recommendation', [
            'travelOrders' => $travelOrders,
            'statuses' => $statuses
        ]);
    }
    
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

        return redirect()->route('my-travel-orders')->with('success', 'Travel order created successfully!');
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

    public function edit(TravelOrderModel $travelOrder)
    {
        return response()->json($travelOrder);
    }

    public function update(Request $request, TravelOrderModel $travelOrder)
    {
        try {
            \Log::info('Update request data:', $request->all());
            
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
                'status_id' => 'required',
            ]);

            \Log::info('Validated data:', $validated);
            
            $updated = $travelOrder->update($validated);
            
            if (!$updated) {
                \Log::error('Failed to update travel order. Model update returned false.');
                return response()->json(['message' => 'Failed to update travel order in database'], 500);
            }

            return response()->json(['message' => 'Travel order updated successfully']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error during travel order update:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Error updating travel order: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $travelOrder = TravelOrderModel::findOrFail($id);
            $travelOrder->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Travel order has been deleted successfully.'
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Travel order has been deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete travel order. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Failed to delete travel order. Please try again.');
        }
    }

    /**
     * Update the status of a travel order
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id, Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|string|in:for approval,disapproved'
            ]);

            $travelOrder = TravelOrderModel::findOrFail($id);
            $user = Auth::user();
            
            // Check if the user is authorized to update the status
            if ($travelOrder->recommender !== $user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this travel order status.'
                ], 403);
            }

            // Get the status ID based on the status name
            $status = TravelOrderStatus::where('name', ucfirst($request->status))->first();
            
            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status provided.'
                ], 422);
            }

            // Update the status
            $travelOrder->status_id = $status->id;
            
            // If recommending for approval, update the approver if not set
            if ($request->status === 'for approval' && !$travelOrder->approver) {
                // Get the default approver from the database or config
                $defaultApprover = 'approver@example.com'; // Replace with your logic to get the default approver
                $travelOrder->approver = $defaultApprover;
            }
            
            $travelOrder->save();

            return response()->json([
                'success' => true,
                'message' => 'Travel order status updated successfully.',
                'status' => $status->name
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating travel order status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the travel order status.'
            ], 500);
        }
    }
}
