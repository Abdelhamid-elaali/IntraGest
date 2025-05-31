<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StockAnalyticsController extends Controller
{
    /**
     * Display the stock analytics dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Get stock value by category with error handling
            $stockByCategory = $this->getStockValueByCategory();
            
            // Get stock movement trends (last 30 days)
            $stockMovement = $this->getStockMovementTrends();
            
            // Get top moving products
            $topProducts = $this->getTopMovingProducts();
            
            // Get expiring products (next 30 days)
            $expiringProducts = $this->getExpiringProducts();
            
            return view('stocks.analytics', compact(
                'stockByCategory', 
                'stockMovement', 
                'topProducts', 
                'expiringProducts'
            ));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Stock Analytics Error: ' . $e->getMessage());
            
            // Return view with error message
            return view('stocks.analytics', [
                'stockByCategory' => collect(),
                'stockMovement' => collect(),
                'topProducts' => collect(),
                'expiringProducts' => collect(),
                'error' => 'There was an error loading the analytics data. Please try again later.'
            ]);
        }
    }
    
    /**
     * Get stock value by category
     *
     * @return \Illuminate\Support\Collection
     */
    private function getStockValueByCategory()
    {
        return Stock::select('category', DB::raw('SUM(quantity * unit_price) as total_value'))
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->get();
    }
    
    /**
     * Get stock movement trends for the last 30 days
     *
     * @return \Illuminate\Support\Collection
     */
    private function getStockMovementTrends()
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        return StockTransaction::select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                DB::raw('SUM(CASE WHEN type = "out" OR type = "transfer_out" THEN quantity ELSE 0 END) as stock_out')
            )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    
    /**
     * Get top moving products
     *
     * @return \Illuminate\Support\Collection
     */
    private function getTopMovingProducts()
    {
        return StockTransaction::select('stock_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('type', 'out')
            ->whereNotNull('stock_id')
            ->groupBy('stock_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->with(['stock' => function($query) {
                $query->select('id', 'name', 'code', 'category', 'unit_type');
            }])
            ->get();
    }
    
    /**
     * Get products expiring in the next 30 days
     *
     * @return \Illuminate\Support\Collection
     */
    private function getExpiringProducts()
    {
        return Stock::where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->where('expiry_date', '>=', Carbon::now())
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')
            ->get();
    }
}
