<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Payment;
use App\Events\DashboardStatsUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with key statistics and recent activities.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Gather statistics
        $stats = [
            'total_students' => User::whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count(),
            'available_rooms' => Room::available()->count(),
            'total_rooms' => Room::count(),
            'recent_payments' => Payment::whereMonth('created_at', now()->month)->sum('amount')
        ];

        // Initialize recent activities collection
        $recentActivities = collect();

        // Recent grades section removed

        // Sort activities by time
        $recentActivities = $recentActivities->sortByDesc('time')->take(5);

        // Performance metrics
        $performanceMetrics = [
            'room_occupancy_rate' => Room::whereHas('currentAllocation')
                ->count() / max(1, Room::count()) * 100
        ];

        // Get recent stock transactions
        $recentTransactions = DB::table('stock_transactions as st')
            ->join('stocks as s', 'st.stock_id', '=', 's.id')
            ->join('users as u', 'st.user_id', '=', 'u.id')
            ->select('st.*', 's.name as stock_name', 'u.name as user_name')
            ->orderBy('st.created_at', 'desc')
            ->take(5)
            ->get();

        // Calculate stock expenses statistics
        $stockStats = DB::table('stock_transactions as st')
            ->join('stocks as s', 'st.stock_id', '=', 's.id')
            ->select(
                DB::raw('SUM(CASE WHEN st.type = "in" THEN (st.quantity * s.unit_price) WHEN st.type = "out" THEN -(st.quantity * s.unit_price) ELSE 0 END) as total_amount'),
                DB::raw('SUM(CASE WHEN st.type = "in" THEN (st.quantity * s.unit_price) WHEN st.type = "out" THEN -(st.quantity * s.unit_price) ELSE 0 END) as supplies_total'),
                DB::raw('0 as services_total'),
                DB::raw('0 as other_total')
            )
            ->where('st.created_at', '>=', now()->subWeek())
            ->first();

        // Calculate percentages
        $total = max($stockStats->total_amount, 1); // Avoid division by zero
        $expenseStats = [
            'supplies' => round(($stockStats->supplies_total / $total) * 100),
            'services' => round(($stockStats->services_total / $total) * 100),
            'other' => round(($stockStats->other_total / $total) * 100)
        ];



        // Get system notifications
        $notifications = [
            [
                'type' => 'success',
                'title' => 'Update Successful',
                'message' => 'The system has been successfully updated to the latest version.'
            ],
            [
                'type' => 'warning',
                'title' => 'Maintenance Scheduled',
                'message' => 'System maintenance is scheduled for tomorrow at 2:00 AM.'
            ],
            [
                'type' => 'error',
                'title' => 'Storage Warning',
                'message' => 'Storage space is running low. Please clean up unnecessary files.'
            ]
        ];

        return view('dashboard.index', compact(
            'stats',
            'recentActivities',
            'performanceMetrics',
            'recentTransactions',
            'expenseStats',
            'notifications',
            // currentTerm removed
        ));
    }
    
    /**
     * Get updated dashboard statistics and broadcast them.
     * This can be called via AJAX or triggered by background jobs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpdatedStats()
    {
        // Gather statistics
        $stats = [
            'total_students' => User::whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count(),
            'available_rooms' => Room::available()->count(),
            'total_rooms' => Room::count(),
            'recent_payments' => Payment::whereMonth('created_at', now()->month)->sum('amount')
        ];
        
        // Calculate stock expenses statistics
        $stockStats = DB::table('stock_transactions as st')
            ->join('stocks as s', 'st.stock_id', '=', 's.id')
            ->select(
                DB::raw('SUM(CASE WHEN st.type = "in" THEN (st.quantity * s.unit_price) WHEN st.type = "out" THEN -(st.quantity * s.unit_price) ELSE 0 END) as total_amount'),
                DB::raw('SUM(CASE WHEN st.type = "in" THEN (st.quantity * s.unit_price) WHEN st.type = "out" THEN -(st.quantity * s.unit_price) ELSE 0 END) as supplies_total'),
                DB::raw('0 as services_total'),
                DB::raw('0 as other_total')
            )
            ->where('st.created_at', '>=', now()->subWeek())
            ->first();

        // Calculate percentages
        $total = max($stockStats->total_amount ?? 0, 1); // Avoid division by zero
        $expenseStats = [
            'supplies' => round((($stockStats->supplies_total ?? 0) / $total) * 100),
            'services' => round((($stockStats->services_total ?? 0) / $total) * 100),
            'other' => round((($stockStats->other_total ?? 0) / $total) * 100)
        ];
        
        // Get recent stock transactions
        $recentTransactions = DB::table('stock_transactions as st')
            ->join('stocks as s', 'st.stock_id', '=', 's.id')
            ->join('users as u', 'st.user_id', '=', 'u.id')
            ->select('st.*', 's.name as stock_name', 'u.name as user_name')
            ->orderBy('st.created_at', 'desc')
            ->take(5)
            ->get();
        
        // Broadcast the updated stats
        broadcast(new DashboardStatsUpdated($stats, $expenseStats, $recentTransactions->toArray()))->toOthers();
        
        return response()->json([
            'stats' => $stats,
            'expenseStats' => $expenseStats,
            'recentTransactions' => $recentTransactions->toArray()
        ]);
    }
}
