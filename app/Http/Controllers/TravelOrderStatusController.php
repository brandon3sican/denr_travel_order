<?php

namespace App\Http\Controllers;

use App\Models\TravelOrderStatus;
use App\Models\TravelOrder as TravelOrderModel;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelOrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statuses = TravelOrderStatus::orderBy('name')->get();

        // Travel Orders listing for reset controls
        $perPage = 10;
        $search = $request->input('search');

        $toQuery = TravelOrderModel::with(['employee', 'status'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $toQuery->where(function ($q) use ($search) {
                $q->where('destination', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($q2) use ($search) {
                      $q2->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('status', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $travelOrders = $toQuery->paginate($perPage)->withQueryString();

        return view('status-management.index', compact('statuses', 'travelOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:travel_order_status,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status = TravelOrderStatus::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status created successfully',
            'status' => $status
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $status = TravelOrderStatus::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:travel_order_status,name,' . $status->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $status = TravelOrderStatus::findOrFail($id);
            
            // Prevent deletion if status is in use
            if ($status->travelOrders()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete status as it is being used by travel orders.'
                ], 422);
            }
            
            $status->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Status deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset an individual travel order's status to For Recommendation or For Approval.
     */
    public function reset(Request $request, $travelOrderId)
    {
        $request->validate([
            'target' => 'required|string|in:for recommendation,for approval'
        ]);

        $travelOrder = TravelOrderModel::with('status')->findOrFail($travelOrderId);

        // Resolve target status
        $targetName = ucwords($request->input('target'));
        $targetStatus = TravelOrderStatus::where('name', $targetName)->first();
        if (!$targetStatus) {
            return back()->with('error', 'Target status not found: ' . $targetName);
        }

        $travelOrder->status_id = $targetStatus->id;
        $travelOrder->save();

        return back()->with('success', 'Travel order #'.$travelOrder->id.' status reset to '.$targetName.'.');
    }
}
