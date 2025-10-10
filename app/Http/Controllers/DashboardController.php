<?php

namespace App\Http\Controllers;

use App\Models\TravelOrder;
use App\Models\TravelOrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $showSignatureAlert = false;
        $userSignature = null;

        // Check if user needs to upload a signature
        if (! $user->is_admin && (! $user->employee || ! $user->employee->signature)) {
            $showSignatureAlert = true;
        } else {
            $userSignature = $user->employee?->signature;
        }

        // Base query for travel orders
        $baseQuery = $user->is_admin
            ? TravelOrder::query()
            : TravelOrder::where('employee_email', $user->email);

        // Apply search filter if provided
        $search = request()->input('search');
        if ($search) {
            $searchTerm = trim($search);
            $searchTerm = strtolower($searchTerm);

            // Split the search term into parts
            $nameParts = explode(' ', $searchTerm);

            $baseQuery->whereHas('employee', function ($q) use ($nameParts) {
                // If search has multiple parts, treat as first and last name
                if (count($nameParts) > 1) {
                    $firstName = $nameParts[0];
                    $lastName = end($nameParts);

                    $q->where(function ($query) use ($firstName, $lastName) {
                        $query->whereRaw('LOWER(first_name) = ?', [$firstName])
                            ->whereRaw('LOWER(last_name) = ?', [$lastName]);
                    });
                } else {
                    // Single word search - check first or last name
                    $q->where(function ($query) use ($nameParts) {
                        $query->whereRaw('LOWER(first_name) = ?', [$nameParts[0]])
                            ->orWhereRaw('LOWER(last_name) = ?', [$nameParts[0]]);
                    });
                }
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
            ->with(['employee' => function ($query) {
                $query->select('id', 'email', 'first_name', 'last_name');
            }])
            ->latest()
            ->paginate(5);

        // Get all travel orders for statistics (without pagination)
        $allTravelOrders = (clone $baseQuery)->get();

        // Calculate statistics
        $totalTravelOrders = $allTravelOrders->count();
        $pendingRequests = $allTravelOrders->whereIn('status_id', [1, 4])->count(); // Including both Pending (1) and For Approval (4) statuses
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

    public function analytics(Request $request)
    {
        $range = $request->input('range', 'week');
        $now = Carbon::now();
        $user = Auth::user();

        // Base query for travel orders
        $baseQuery = $user->is_admin
            ? TravelOrder::query()
            : TravelOrder::where('employee_email', $user->email);

        // Get start date based on selected range
        $startDate = match($range) {
            'day' => $now->copy()->startOfDay(),
            'month' => $now->copy()->startOfMonth(),
            'year' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfWeek(),
        };

        // Initialize chart data structure
        $chartData = [
            'labels' => [],
            'datasets' => [
                'total' => [],
                'pending' => [],
                'completed' => [],
                'cancelled' => []
            ]
        ];

        // Get interval based on range
        $interval = match($range) {
            'day' => 'hour',
            'month' => 'day',
            'year' => 'month',
            default => 'day', // week
        };

        // Generate date periods
        $endDate = Carbon::now();
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1' . strtoupper(substr($interval, 0, 1))),
            $endDate
        );

        // Get data for each period
        foreach ($period as $date) {
            // Format label based on interval
            $label = match($interval) {
                'hour' => $date->format('H:00'),
                'day' => $date->format('M j'),
                'month' => $date->format('M Y'),
                default => $date->format('D'),
            };
            $chartData['labels'][] = $label;

            // Total orders
            $chartData['datasets']['total'][] = (clone $baseQuery)
                ->whereDate('created_at', $date)
                ->count();

            // Pending/For Approval orders
            $chartData['datasets']['pending'][] = (clone $baseQuery)
                ->whereIn('status_id', [1, 4]) // Pending and For Approval
                ->whereDate('created_at', $date)
                ->count();

            // Completed orders
            $chartData['datasets']['completed'][] = (clone $baseQuery)
                ->whereIn('status_id', [2, 3]) // Approved and Completed
                ->whereDate('created_at', $date)
                ->count();

            // Cancelled orders
            $chartData['datasets']['cancelled'][] = (clone $baseQuery)
                ->where('status_id', 5) // Cancelled
                ->whereDate('created_at', $date)
                ->count();
        }

        // Get status distribution for pie chart
        $statusData = $this->getStatusDistribution($baseQuery, $startDate);

        return response()->json([
            'chartData' => $chartData,
            'statusData' => $statusData,
        ]);
    }

    private function getPendingOrdersData($query, $startDate, $range)
    {
        $endDate = Carbon::now();
        $interval = match($range) {
            'day' => 'hour',
            'month' => 'day',
            'year' => 'month',
            default => 'day', // week
        };

        // Get pending/for approval orders
        $pendingQuery = (clone $query)
            ->whereIn('status_id', [1, 4]) // Pending and For Approval
            ->where('created_at', '>=', $startDate);

        // Group by time interval
        $grouped = $pendingQuery->get()
            ->groupBy(function($item) use ($interval) {
                return $item->created_at->format($interval === 'hour' ? 'H:00' : 
                    ($interval === 'day' ? 'M j' : 
                    ($interval === 'month' ? 'M Y' : 'Y')));
            });

        // Generate labels and data
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1' . strtoupper(substr($interval, 0, 1))),
            $endDate
        );

        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $key = $date->format($interval === 'hour' ? 'H:00' : 
                    ($interval === 'day' ? 'M j' : 
                    ($interval === 'month' ? 'M Y' : 'Y')));
            $labels[] = $key;
            $data[] = $grouped->has($key) ? $grouped[$key]->count() : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getStatusDistribution($query, $startDate)
    {
        $statuses = TravelOrderStatus::all();
        $statusData = [];
        $statusLabels = [];

        foreach ($statuses as $status) {
            $count = (clone $query)
                ->where('status_id', $status->id)
                ->where('created_at', '>=', $startDate)
                ->count();

            if ($count > 0) {
                $statusLabels[] = $status->name;
                $statusData[] = $count;
            }
        }

        return [
            'labels' => $statusLabels,
            'data' => $statusData
        ];
    }
}
