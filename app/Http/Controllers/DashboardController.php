<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Grade;
use App\Models\AcademicTerm;
use App\Models\Payment;
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
        // Get current academic term
        $currentTerm = AcademicTerm::current()->first();

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

        // Recent grades
        $recentGrades = Grade::with('student')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($grade) {
                return [
                    'type' => 'grade',
                    'description' => "Grade recorded for {$grade->student->name}",
                    'user' => $grade->student->name,
                    'time' => $grade->created_at,
                    'status' => $grade->is_final ? 'final' : 'pending'
                ];
            });
        $recentActivities = $recentActivities->concat($recentGrades);

        // Sort activities by time
        $recentActivities = $recentActivities->sortByDesc('time')->take(5);

        // Get performance metrics for current term
        $performanceMetrics = [];
        if ($currentTerm) {
            $studentCount = User::whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count();

            $performanceMetrics = [
                'average_grade' => Grade::where('academic_term_id', $currentTerm->id)
                    ->avg('score') ?? 0,
                'passing_rate' => Grade::where('academic_term_id', $currentTerm->id)
                    ->where('score', '>=', 60)
                    ->count() / max(1, Grade::where('academic_term_id', $currentTerm->id)
                    ->count()) * 100,
                'room_occupancy_rate' => Room::whereHas('currentAllocation')
                    ->count() / max(1, Room::count()) * 100
            ];
        }

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
            'currentTerm'
        ));
    }
}
