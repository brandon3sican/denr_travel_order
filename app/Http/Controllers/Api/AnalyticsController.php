<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TravelOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function getTravelOrderAnalytics(Request $request)
    {
        try {
            \Log::info('Analytics Request:', $request->all());

            $period = $request->input('period', 'monthly');
            $timezone = $request->input('timezone', 'UTC');
            $month = $request->input('month', null);
            $year = $request->input('year', date('Y'));

            $user = Auth::user();
            if (! $user) {
                throw new \Exception('User not authenticated');
            }

            // Set the timezone for date calculations
            if (in_array($timezone, timezone_identifiers_list())) {
                date_default_timezone_set($timezone);
            }

            // Base query - admin sees all, regular users see their own
            $baseQuery = $user->is_admin
                ? TravelOrder::query()
                : TravelOrder::where('employee_email', $user->email);

            // Clone the base query for line chart to avoid interference
            $lineChartQuery = (clone $baseQuery);
            $pieChartQuery = (clone $baseQuery);

            // Get data for charts
            $lineChartData = $this->getLineChartData($lineChartQuery, $period, $month, $year);
            $pieChartData = $this->getPieChartData($pieChartQuery, $period, $month, $year);

            $response = [
                'success' => true,
                'lineChart' => $lineChartData,
                'pieChart' => $pieChartData,
                'debug' => [
                    'period' => $period,
                    'month' => $month,
                    'year' => $year,
                    'timezone' => $timezone,
                    'user' => $user->email,
                    'is_admin' => $user->is_admin,
                ],
            ];

            \Log::info('Analytics Response:', $response['debug']);

            return response()->json($response);

        } catch (\Exception $e) {
            $errorData = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ];

            \Log::error('Analytics Error:', $errorData);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load analytics data',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'debug' => config('app.debug') ? $errorData : null,
            ], 500);
        }
    }

    private function getLineChartData($query, $period, $month = null, $year = null)
    {
        $endDate = Carbon::now();
        $startDate = null;
        $dateFormat = '';
        $interval = '';
        $labels = [];

        // Convert month to integer if it's not null
        $month = $month !== null ? (int) $month : null;
        $year = $year !== null ? (int) $year : (int) date('Y');

        switch ($period) {
            case 'daily':
                if ($month && $year) {
                    $startDate = Carbon::create($year, $month, 1);
                    $endDate = $startDate->copy()->endOfMonth();
                } else {
                    $startDate = $endDate->copy()->subDays(13); // Last 14 days
                    $endDate = $endDate->copy()->endOfDay();
                }

                // Generate day labels
                $current = $startDate->copy();
                while ($current <= $endDate) {
                    $labels[] = $current->format('M j');
                    $current->addDay();
                }

                $dateFormat = 'Y-m-d';
                $interval = '1 day';
                break;

            case 'weekly':
                if ($month && $year) {
                    $startDate = Carbon::create($year, $month, 1);
                    $endDate = $startDate->copy()->endOfMonth();
                } elseif ($year) {
                    $startDate = Carbon::create($year, 1, 1);
                    $endDate = $startDate->copy()->endOfYear();
                } else {
                    $startDate = $endDate->copy()->subWeeks(11);
                    $endDate = $endDate->copy()->endOfDay();
                }

                // Generate week labels
                $current = $startDate->copy()->startOfWeek();
                while ($current <= $endDate) {
                    $weekEnd = $current->copy()->endOfWeek();
                    $labels[] = $current->format('M d').' - '.$weekEnd->format('M d');
                    $current->addWeek();
                }

                $interval = '1 week';
                break;

            case 'monthly':
                if ($year) {
                    $startDate = Carbon::create($year, 1, 1);
                    $endDate = $startDate->copy()->endOfYear();
                    $dateFormat = 'M';

                    // Generate month labels
                    for ($m = 1; $m <= 12; $m++) {
                        $labels[] = date('M', mktime(0, 0, 0, $m, 1, $year));
                    }
                } else {
                    $startDate = $endDate->copy()->subMonths(11);
                    $dateFormat = 'M Y';

                    // Generate month labels for last 12 months
                    $current = $startDate->copy();
                    while ($current <= $endDate) {
                        $labels[] = $current->format('M Y');
                        $current->addMonth();
                    }
                }
                $interval = '1 month';
                break;

            case 'yearly':
                $startDate = $endDate->copy()->subYears(4); // Last 5 years
                $dateFormat = 'Y';

                // Generate year labels
                $current = $startDate->copy();
                while ($current <= $endDate) {
                    $labels[] = $current->format('Y');
                    $current->addYear();
                }

                $interval = '1 year';
                break;

            default:
                $startDate = $endDate->copy()->subMonths(11);
                $dateFormat = 'M Y';
                $interval = '1 month';

                // Generate month labels for last 12 months
                $current = $startDate->copy();
                while ($current <= $endDate) {
                    $labels[] = $current->format('M Y');
                    $current->addMonth();
                }
        }

        // Get data for each status
        $statuses = [
            'pending' => [
                'query' => function ($q) {
                    $q->whereIn('status_id', [1, 2]);
                }, // Combined For Recommendation (1) and For Approval (2)
                'breakdown' => [
                    'for_recommendation' => function ($q) {
                        $q->where('status_id', 1);
                    },
                    'for_approval' => function ($q) {
                        $q->where('status_id', 2);
                    },
                ],
            ],
            'completed' => [
                'query' => function ($q) {
                    $q->where('status_id', 6);
                }, // Completed
            ],
            'cancelled' => [
                'query' => function ($q) {
                    $q->where('status_id', 5);
                }, // Cancelled
            ],
            'disapproved' => [
                'query' => function ($q) {
                    $q->where('status_id', 4);
                }, // Disapproved
            ],
        ];

        $data = [];
        $breakdowns = [];

        foreach ($statuses as $status => $statusConfig) {
            $statusData = [];
            $current = $startDate->copy();

            if ($period === 'weekly') {
                $current = $startDate->copy()->startOfWeek();
            }

            while ($current <= $endDate) {
                $next = $current->copy()->add($interval);

                // Main query for this status
                $statusQuery = (clone $query)
                    ->whereBetween('created_at', [
                        $current->copy()->startOfDay(),
                        $next->copy()->subSecond(),
                    ]);

                // Apply the main query condition
                if (isset($statusConfig['query']) && is_callable($statusConfig['query'])) {
                    $statusConfig['query']($statusQuery);
                }

                $count = $statusQuery->count();
                $statusData[] = $count;

                // If this status has a breakdown, get the breakdown data
                if (isset($statusConfig['breakdown'])) {
                    foreach ($statusConfig['breakdown'] as $breakdownKey => $breakdownCondition) {
                        $breakdownQuery = (clone $query)
                            ->whereBetween('created_at', [
                                $current->copy()->startOfDay(),
                                $next->copy()->subSecond(),
                            ]);

                        $breakdownCondition($breakdownQuery);
                        $breakdownCount = $breakdownQuery->count();

                        if (! isset($breakdowns[$current->format('Y-m-d')])) {
                            $breakdowns[$current->format('Y-m-d')] = [];
                        }

                        $breakdowns[$current->format('Y-m-d')][$breakdownKey] = $breakdownCount;
                    }
                }

                $current = $next;
            }

            // If we have more data points than labels, trim to match
            if (count($statusData) > count($labels)) {
                $statusData = array_slice($statusData, 0, count($labels));
            }

            $data[$status] = $statusData;
        }

        return [
            'labels' => $labels,
            'pendingData' => $data['pending'],
            'completedData' => $data['completed'],
            'cancelledData' => $data['cancelled'],
            'disapprovedData' => $data['disapproved'],
            'breakdowns' => $breakdowns,
        ];
    }

    private function getPieChartData($query)
    {
        // Define all statuses with their IDs, labels, and colors
        $statuses = [
            [
                'id' => 1,
                'label' => 'For Recommendation',
                'bgColor' => 'rgba(249, 168, 37, 0.7)',   // Orange
                'borderColor' => 'rgba(249, 168, 37, 1)',
            ],
            [
                'id' => 2,
                'label' => 'For Approval',
                'bgColor' => 'rgba(59, 130, 246, 0.7)',   // Blue
                'borderColor' => 'rgba(59, 130, 246, 1)',
            ],
            [
                'id' => 3,
                'label' => 'Approved',
                'bgColor' => 'rgba(16, 185, 129, 0.7)',   // Green
                'borderColor' => 'rgba(16, 185, 129, 1)',
            ],
            [
                'id' => 6,
                'label' => 'Completed',
                'bgColor' => 'rgba(139, 92, 246, 0.7)',   // Purple
                'borderColor' => 'rgba(139, 92, 246, 1)',
            ],
            [
                'id' => 5,
                'label' => 'Cancelled',
                'bgColor' => 'rgba(107, 114, 128, 0.7)',  // Gray
                'borderColor' => 'rgba(107, 114, 128, 1)',
            ],
            [
                'id' => 4,
                'label' => 'Disapproved',
                'bgColor' => 'rgba(239, 68, 68, 0.7)',    // Red
                'borderColor' => 'rgba(239, 68, 68, 1)',
            ],
        ];

        // Prepare data arrays
        $labels = [];
        $data = [];
        $backgroundColors = [];
        $borderColors = [];
        $counts = [];

        // Get counts for each status
        foreach ($statuses as $status) {
            $count = (clone $query)->where('status_id', $status['id'])->count();
            if ($count > 0) {
                $labels[] = $status['label'];
                $data[] = $count;
                $backgroundColors[] = $status['bgColor'];
                $borderColors[] = $status['borderColor'];
                $counts[$status['label'].'_count'] = $count;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'backgroundColors' => $backgroundColors,
            'borderColors' => $borderColors,
            'counts' => $counts,
        ];
    }
}
