<?php

namespace App\Http\Controllers;

use App\Models\TravelOrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelOrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = TravelOrderStatus::orderBy('name')->get();
        return view('status-management.index', compact('statuses'));
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
}
