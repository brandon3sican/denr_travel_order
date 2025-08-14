<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use App\Models\TravelOrderStatus;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function travelVolume(Request $request)
    {
        $timeframe = $request->input('timeframe', 'monthly');
        $status = $request->input('status');
        
        $query = TravelOrder::query();
        
        if ($status) {
            $query->whereHas('status', function($q) use ($status) {
                $q->where('name', $status);
            });
        }
        
        $data = $this->groupByTimeframe($query, $timeframe);
        
        return view('reports.volume', [
            'data' => $data,
            'timeframe' => $timeframe,
            'status' => $status
        ]);
    }

    public function approvalMetrics()
    {
        $metrics = [
            'avg_approval_time' => $this->getAverageApprovalTime(),
            'pending_approvals' => $this->getPendingApprovals(),
            'approval_workload' => $this->getApproverWorkload()
        ];
        
        return view('reports.approval-metrics', [
            'metrics' => $metrics
        ]);
    }

    public function employeeTravelPatterns()
    {
        $topTravelers = User::withCount(['travelOrders as travel_count' => function($query) {
            $query->join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
                  ->where('travel_order_status.name', '!=', 'cancelled');
        }])
        ->orderBy('travel_count', 'desc')
        ->limit(10)
        ->get();

        $frequentDestinations = TravelOrder::select('destination', DB::raw('count(*) as total'))
            ->groupBy('destination')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
            
        return view('reports.employee-travel', [
            'topTravelers' => $topTravelers,
            'frequentDestinations' => $frequentDestinations
        ]);
    }

    public function operationalEfficiency()
    {
        $processingTimes = $this->getProcessingTimes();
        $cancellationRates = $this->getCancellationRates();
        
        return view('reports.operational-efficiency', [
            'processingTimes' => $processingTimes,
            'cancellationRates' => $cancellationRates
        ]);
    }

    public function departmentReports()
    {
        $departmentStats = Employee::select('assignment_name as department')
            ->selectRaw('count(travel_orders.id) as travel_count')
            ->leftJoin('travel_orders', 'employees.email', '=', 'travel_orders.employee_email')
            ->whereNotNull('travel_orders.id')
            ->groupBy('department')
            ->orderBy('travel_count', 'desc')
            ->get();
            
        return view('reports.department', [
            'departmentStats' => $departmentStats
        ]);
    }

    // Helper Methods
    private function groupByTimeframe($query, $timeframe)
    {
        $format = $timeframe === 'yearly' ? 'Y' : ($timeframe === 'quarterly' ? 'Y-Q' : 'Y-m');
        
        return $query->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-01') as period"),
                DB::raw('count(*) as total')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    private function getAverageApprovalTime()
    {
        return TravelOrder::join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, travel_orders.created_at, travel_orders.updated_at)) as avg_hours')
            ->where('travel_order_status.name', 'approved')
            ->first()
            ->avg_hours ?? 0;
    }

    private function getPendingApprovals()
    {
        return TravelOrder::join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
            ->whereIn('travel_order_status.name', ['pending', 'submitted'])
            ->count();
    }

    private function getApproverWorkload()
    {
        // Get users who have the approver role
        $approverRole = \App\Models\TravelOrderRole::where('name', 'approver')->first();
        
        if (!$approverRole) {
            return collect();
        }
        
        // Get users with the approver role
        $approvers = User::whereHas('travelOrderRoles', function($query) use ($approverRole) {
                $query->where('travel_order_roles.id', $approverRole->id);
            })
            ->withCount(['travelOrdersToApprove as pending_approval_count' => function($query) {
                $query->join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
                    ->whereIn('travel_order_status.name', ['pending', 'submitted']);
            }])
            ->whereHas('travelOrdersToApprove', function($query) {
                $query->join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
                    ->whereIn('travel_order_status.name', ['pending', 'submitted']);
            })
            ->orderBy('pending_approval_count', 'desc')
            ->get();
            
        return $approvers;
    }

    private function getProcessingTimes()
    {
        return [
            'submission_to_approval' => TravelOrder::join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, travel_orders.created_at, travel_orders.updated_at)) as avg_hours')
                ->where('travel_order_status.name', 'approved')
                ->first()
                ->avg_hours ?? 0,
            'approval_to_completion' => TravelOrder::join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, travel_orders.created_at, travel_orders.updated_at)) as avg_hours')
                ->where('travel_order_status.name', 'completed')
                ->first()
                ->avg_hours ?? 0
        ];
    }

    private function getCancellationRates()
    {
        $total = TravelOrder::count();
        $cancelled = TravelOrder::join('travel_order_status', 'travel_orders.status_id', '=', 'travel_order_status.id')
            ->where('travel_order_status.name', 'cancelled')
            ->count();
        
        return $total > 0 ? ($cancelled / $total) * 100 : 0;
    }
}
