<?php

namespace App\Console\Commands;

use App\Events\DashboardStatsUpdated;
use App\Models\User;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDashboardStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:update-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dashboard statistics and broadcast to all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating dashboard statistics...');

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
            ->get()
            ->toArray();

        // Broadcast the updated stats
        broadcast(new DashboardStatsUpdated($stats, $expenseStats, $recentTransactions));

        $this->info('Dashboard statistics updated and broadcasted successfully!');
    }
}
