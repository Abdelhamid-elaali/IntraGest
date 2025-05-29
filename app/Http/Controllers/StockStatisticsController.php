<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockStatisticsController extends Controller
{
    public function getExpenseStats(Request $request)
    {
        $range = $request->get('range', 'week');
        
        $query = DB::table('stock_transactions as st')
            ->join('stocks as s', 'st.stock_id', '=', 's.id');
        
        switch ($range) {
            case 'month':
                $query->where('st.created_at', '>=', now()->subMonth());
                break;
            case 'year':
                $query->where('st.created_at', '>=', now()->subYear());
                break;
            default: // week
                $query->where('st.created_at', '>=', now()->subWeek());
                break;
        }
        
        $stats = $query->select(
            DB::raw('SUM(CASE WHEN st.type = "in" THEN (st.quantity * s.unit_price) WHEN st.type = "out" THEN -(st.quantity * s.unit_price) ELSE 0 END) as total_amount'),
            DB::raw('SUM(CASE WHEN st.type = "in" THEN (st.quantity * s.unit_price) WHEN st.type = "out" THEN -(st.quantity * s.unit_price) ELSE 0 END) as supplies_total'),
            DB::raw('0 as services_total'),
            DB::raw('0 as other_total')
        )->first();

        $total = max($stats->total_amount, 1); // Avoid division by zero

        return response()->json([
            'supplies' => round(($stats->supplies_total / $total) * 100),
            'services' => round(($stats->services_total / $total) * 100),
            'other' => round(($stats->other_total / $total) * 100)
        ]);
    }
}
