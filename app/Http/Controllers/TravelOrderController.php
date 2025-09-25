<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSignature;
use App\Models\TravelOrder as TravelOrderModel;
use App\Models\TravelOrderNumber;
use App\Models\TravelOrderRole;
use App\Models\TravelOrderStatus;
use App\Models\TravelOrderStatusHistory;
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
            ->oldest()
            ->paginate(10);
            
        $statuses = TravelOrderStatus::all();
            
        return view('travel-orders.for-approval', [
            'travelOrders' => $travelOrders,
            'statuses' => $statuses
        ]);
    }

    /**
     * Approve a travel order
     */
    public function approve(Request $request, $id)
    {
        $travelOrder = TravelOrderModel::findOrFail($id);
        
        // Check if already approved
        if ($travelOrder->status->name === 'Approved') {
            return response()->json([
                'success' => false,
                'message' => 'This travel order has already been approved.'
            ]);
        }

        DB::beginTransaction();
        try {
            // Capture previous status, then update to Approved
            $prevStatusName = optional($travelOrder->status)->name ?? null;
            $approvedStatus = TravelOrderStatus::where('name', 'Approved')->first();
            $travelOrder->status_id = $approvedStatus->id;
            $travelOrder->save();

            // Log metadata
            TravelOrderStatusHistory::create([
                'travel_order_id' => $travelOrder->id,
                'user_id' => Auth::id(),
                'action' => 'approve',
                'from_status' => $prevStatusName,
                'to_status' => 'Approved',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'device' => $request->input('client_meta.device') ?? null,
                'browser' => $request->input('client_meta.browser') ?? null,
                'location' => $request->input('location') ?? null,
                'client_meta' => $request->input('client_meta') ?? null,
            ]);

            // Generate travel order number (format: TO-YYYYMMDD-XXXX)
            $datePrefix = now()->format('Ymd');
            $lastOrder = TravelOrderNumber::orderBy('id', 'desc')->first();
            $sequence = $lastOrder ? (int)substr($lastOrder->travel_order_number, -4) + 1 : 1;
            $travelOrderNumber = 'TO-' . $datePrefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Save travel order number
            TravelOrderNumber::create([
                'travel_order_number' => $travelOrderNumber,
                'travel_order_id' => $travelOrder->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Travel order approved successfully.',
                'travel_order_number' => $travelOrderNumber
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving travel order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve travel order. Please try again.'
            ], 500);
        }
    }

    /**
     * Reject a travel order
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $travelOrder = TravelOrderModel::findOrFail($id);
        
        // Check if already rejected
        if ($travelOrder->status->name === 'Disapproved') {
            return response()->json([
                'success' => false,
                'message' => 'This travel order has already been rejected.'
            ]);
        }

        try {
            // Capture previous status, then update to Disapproved
            $prevStatusName = optional($travelOrder->status)->name ?? null;
            $rejectedStatus = TravelOrderStatus::where('name', 'Disapproved')->first();
            $travelOrder->status_id = $rejectedStatus->id;
            $travelOrder->remarks = $request->reason;
            $travelOrder->save();

            // Log metadata
            TravelOrderStatusHistory::create([
                'travel_order_id' => $travelOrder->id,
                'user_id' => Auth::id(),
                'action' => 'reject',
                'from_status' => $prevStatusName,
                'to_status' => 'Disapproved',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'device' => $request->input('client_meta.device') ?? null,
                'browser' => $request->input('client_meta.browser') ?? null,
                'location' => $request->input('location') ?? null,
                'client_meta' => $request->input('client_meta') ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Travel order has been rejected.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error rejecting travel order: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject travel order. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Display travel orders history
     *
     * @return \Illuminate\View\View
     */
    public function history()
    {
        $user = Auth::user();
        $perPage = request()->integer('per_page', 10);
        $search = request('search');

        $query = TravelOrderModel::with(['employee', 'status'])
            ->where('employee_email', $user->email);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('destination', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        $travelOrders = $query->orderBy('created_at', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Load travel history actions for the current user
        $allHistory = \App\Models\TravelOrderStatusHistory::with(['travelOrder.status', 'user'])
            ->where('user_id', $user->id) // Only show actions taken by the current user
            ->whereHas('travelOrder') // Ensure the travel order still exists
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('travel-orders.history', [
            'travelOrders' => $travelOrders,
            'allHistory' => $allHistory,
        ]);
    }
    
    /**
     * Mark a travel order as completed
     *
     * @param  \App\Models\TravelOrder  $travel_order
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(TravelOrderModel $travel_order, Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'documents' => 'required|array|min:1',
                'documents.*' => 'required|file|mimes:pdf|max:10240', // 10MB max per file
            ]);
            
            // Check if the travel order can be marked as completed
            if ($travel_order->status->name !== 'Approved') {
                return response()->json([
                    'message' => 'Only approved travel orders can be marked as completed.'
                ], 422);
            }
            
            // Process file uploads
            $uploadedFiles = [];
            
            if ($request->hasFile('documents')) {
                $path = "travel-orders/{$travel_order->id}/documents";
                
                foreach ($request->file('documents') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($path, $fileName, 'public');
                    $uploadedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $filePath,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ];
                }
            }
            
            // Update travel order status to completed
            $completedStatus = TravelOrderStatus::where('name', 'Completed')->firstOrFail();
            $travel_order->update([
                'status_id' => $completedStatus->id,
                'completed_at' => now(),
                'completed_by' => Auth::user()->id,
                'documents' => $uploadedFiles,
            ]);
            
            return response()->json([
                'message' => 'Travel order has been marked as completed successfully.',
                'data' => $travel_order->fresh()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error completing travel order: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to complete travel order. ' . $e->getMessage()
            ], 500);
        }
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
            ->oldest()
            ->paginate(10);
            
        $statuses = TravelOrderStatus::all();
            
        return view('travel-orders.for-recommendation', [
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
        $perPage = request()->input('per_page', 6);
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

        $travelOrders = $query->orderBy('travel_orders.created_at', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        $statuses = TravelOrderStatus::all();
        
        // Get the list of employees who can recommend (travel_order_role_id 3 or 5)
        $recommenders = Employee::whereHas('user.travelOrderRoles', function($query) {
                $query->whereIn('travel_order_role_id', [3, 5]);
            })
            ->with('user')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
            
        // Get the list of employees who can approve (travel_order_role_id 4 or 5)
        $approvers = Employee::whereHas('user.travelOrderRoles', function($query) {
                $query->whereIn('travel_order_role_id', [4, 5]);
            })
            ->with('user')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('travel-orders.admin.index', [
            'travelOrders' => $travelOrders,
            'statuses' => $statuses,
            'recommenders' => $recommenders,
            'approvers' => $approvers,
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
            
        return view('travel-orders.create', compact('employee', 'recommenders', 'approvers'));
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

        return redirect()->route('dashboard')->with('success', 'Travel order created successfully!');
    }

    public function show($id)
    {
        $travelOrder = TravelOrderModel::with([
            'employee', 
            'status', 
            'recommenderEmployee', 
            'approverEmployee',
            'statusHistories.user'
        ])->findOrFail($id);

        // Helper function to add signature URL
        $addSignatureUrl = function ($employee) {
            if ($employee && $employee->signature) {
                $signaturePath = $employee->signature->signature_path;
                $employee->signature->signature_url = asset('storage/' . ltrim($signaturePath, '/'));
                
                // Log the signature URL for debugging
                if (isset($employee->id)) {
                    Log::info('Signature URL:', [
                        'employee_id' => $employee->id,
                        'name' => ($employee->first_name ?? '') . ' ' . ($employee->last_name ?? ''),
                        'signature_path' => $signaturePath,
                        'full_url' => $employee->signature->signature_url
                    ]);
                }
            }
            return $employee;
        };

        // Add signature URLs to the employee data
        $employeeData = [];
        if ($travelOrder->employee) {
            $employeeData['employee'] = $addSignatureUrl($travelOrder->employee);
        }
        if ($travelOrder->recommenderEmployee) {
            $employeeData['recommender_employee'] = $addSignatureUrl($travelOrder->recommenderEmployee);
        }
        if ($travelOrder->approverEmployee) {
            $employeeData['approver_employee'] = $addSignatureUrl($travelOrder->approverEmployee);
        }
        
        // Add the employee data to the travel order as a new property
        $travelOrder->employee_data = (object)$employeeData;

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json($travelOrder);
        }

        return view('travel-orders.show', [
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
            Log::info('Update request data:', $request->all());
            
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

            Log::info('Validated data:', $validated);
            
            $updated = $travelOrder->update($validated);
            
            if (!$updated) {
                Log::error('Failed to update travel order. Model update returned false.');
                return response()->json(['message' => 'Failed to update travel order in database'], 500);
            }

            return response()->json(['message' => 'Travel order updated successfully']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error during travel order update:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error updating travel order: ' . $e->getMessage(), [
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
                'status' => 'required|string|in:for approval,disapproved',
                'location' => 'nullable',
                'client_meta' => 'nullable',
            ]);

            $travelOrder = TravelOrderModel::with('status')->findOrFail($id);
            $user = Auth::user();
            
            // Check if the user is authorized to update the status
            if ($travelOrder->recommender !== $user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this travel order status.'
                ], 403);
            }

            // Get the status ID based on the status name
            // Normalize input status to DB-friendly name
            $map = [
                'for approval' => 'For Approval',
                'disapproved' => 'Disapproved',
            ];
            $requested = strtolower($request->status);
            $normalized = $map[$requested] ?? null;
            $status = $normalized ? TravelOrderStatus::where('name', $normalized)->first() : null;
            
            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status provided.'
                ], 422);
            }

            // Capture previous status, then update the status
            $prevStatusName = optional($travelOrder->status)->name ?? null;
            $travelOrder->status_id = $status->id;
            
            // If recommending for approval, update the approver if not set
            if ($request->status === 'for approval' && !$travelOrder->approver) {
                // Get the default approver from the database or config
                $defaultApprover = 'approver@example.com'; // Replace with your logic to get the default approver
                $travelOrder->approver = $defaultApprover;
            }
            
            $travelOrder->save();

            // Log metadata
            $action = $request->status === 'for approval' ? 'recommend' : 'update_status';
            TravelOrderStatusHistory::create([
                'travel_order_id' => $travelOrder->id,
                'user_id' => Auth::id(),
                'action' => $action,
                'from_status' => $prevStatusName,
                'to_status' => $status->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'device' => $request->input('client_meta.device') ?? null,
                'browser' => $request->input('client_meta.browser') ?? null,
                'location' => $request->input('location') ?? null,
                'client_meta' => $request->input('client_meta') ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Travel order status updated successfully.',
                'status' => $status->name
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating travel order status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the travel order status.'
            ], 500);
        }
    }

    /**
     * Update the recommender and approver of a travel order
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TravelOrder  $travel_order
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateApprovers(Request $request, TravelOrderModel $travel_order)
    {
        $request->validate([
            'recommender' => 'required|email|exists:employees,email',
            'approver' => 'required|email|exists:employees,email'
        ]);

        try {
            DB::beginTransaction();

            // Store the previous values for the status history
            $previousRecommender = $travel_order->recommender;
            $previousApprover = $travel_order->approver;

            // Update the travel order
            $travel_order->update([
                'recommender' => $request->recommender,
                'approver' => $request->approver
            ]);

            // Refresh the model to get the latest data
            $travel_order->refresh();

            // Log the change in status history
            if ($previousRecommender !== $request->recommender || $previousApprover !== $request->approver) {
                $statusName = $travel_order->status ? $travel_order->status->name : 'Unknown';
                
                TravelOrderStatusHistory::create([
                    'travel_order_id' => $travel_order->id,
                    'user_id' => auth()->id(),
                    'action' => 'update_approvers',
                    'from_status' => $statusName,
                    'to_status' => $statusName,
                    'metadata' => json_encode([
                        'previous_recommender' => $previousRecommender,
                        'new_recommender' => $request->recommender,
                        'previous_approver' => $previousApprover,
                        'new_approver' => $request->approver,
                        'updated_by' => auth()->id()
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'device' => $request->input('client_meta.device') ?? null,
                    'browser' => $request->input('client_meta.browser') ?? null,
                    'location' => $request->input('location') ?? null,
                    'client_meta' => $request->input('client_meta') ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Approvers updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating approvers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update approvers: ' . $e->getMessage()
            ], 500);
        }
    }
}
