<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Stock;
use App\Models\StockOrder;
use Carbon\Carbon;

class Supplier extends Model
{
    use HasFactory; // SoftDeletes removed temporarily

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'tax_number',
        'website',
        'notes',
        'status'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    
    public function orders()
    {
        return $this->hasMany(StockOrder::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getFullAddress()
    {
        return "{$this->address}, {$this->city}, {$this->country}";
    }

    public function getTotalPurchases()
    {
        // Calculate from stock transactions
        $fromTransactions = $this->stocks()
            ->withSum('transactions', 'quantity')
            ->where('stock_transactions.type', 'in')
            ->get()
            ->sum('transactions_sum_quantity');
            
        // Calculate from stock orders
        $fromOrders = $this->orders()
            ->where('status', 'delivered')
            ->sum('total_amount');
            
        return $fromOrders;
    }

    public function activeStocks()
    {
        return $this->stocks()->where('quantity', '>', 0)->where('status', 'active');
    }

    public function lowStockItems()
    {
        return $this->stocks()->whereRaw('quantity <= minimum_quantity');
    }

    public function expiringStocks($days = 30)
    {
        return $this->stocks()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now());
    }
    
    /**
     * Get pending orders for this supplier
     */
    public function pendingOrders()
    {
        return $this->orders()->where('status', 'pending');
    }
    
    /**
     * Get approved orders for this supplier
     */
    public function approvedOrders()
    {
        return $this->orders()->where('status', 'approved');
    }
    
    /**
     * Get delivered orders for this supplier
     */
    public function deliveredOrders()
    {
        return $this->orders()->where('status', 'delivered');
    }
    
    /**
     * Get total orders amount for this supplier
     */
    public function totalOrdersAmount()
    {
        return $this->orders()->sum('total_amount');
    }
    
    /**
     * Get orders in date range
     */
    public function ordersInDateRange($startDate, $endDate)
    {
        return $this->orders()
            ->whereBetween('order_date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
    }
}
