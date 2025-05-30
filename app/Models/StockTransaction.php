<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'type',
        'quantity',
        'unit_price',
        'user_id',
        'notes',
        'reference_number',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'unit_price' => 'decimal:2',
        'transaction_date' => 'datetime'
    ];

    // Relationships
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the transaction value (quantity * unit_price)
     */
    public function getValue()
    {
        return $this->quantity * $this->unit_price;
    }

    // Helper methods
    public function isStockIn()
    {
        return $this->type === 'in';
    }

    public function isStockOut()
    {
        return $this->type === 'out';
    }

    public function calculateTotalAmount()
    {
        if ($this->unit_price && $this->quantity) {
            $this->total_amount = $this->unit_price * $this->quantity;
            $this->save();
        }
        return $this->total_amount ?? 0;
    }

    // Scopes
    /**
     * Scope for stock additions
     */
    public function scopeStockIn($query)
    {
        return $query->where('type', 'in');
    }

    /**
     * Scope for stock removals
     */
    public function scopeStockOut($query)
    {
        return $query->where('type', 'out');
    }
    
    /**
     * Scope for initial stock entries
     */
    public function scopeInitial($query)
    {
        return $query->where('type', 'initial');
    }
    
    /**
     * Scope for stock transfers out
     */
    public function scopeTransferOut($query)
    {
        return $query->where('type', 'transfer_out');
    }
    
    /**
     * Scope for stock transfers in
     */
    public function scopeTransferIn($query)
    {
        return $query->where('type', 'transfer_in');
    }
    
    /**
     * Scope for price changes
     */
    public function scopePriceChange($query)
    {
        return $query->where('type', 'price_change');
    }
    
    /**
     * Scope for transactions in a date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }
    
    /**
     * Scope for transactions today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('transaction_date', Carbon::today());
    }
    
    /**
     * Scope for transactions this week
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('transaction_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }
    
    /**
     * Scope for transactions this month
     */
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('transaction_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }

    public function scopeByStock($query, $stockId)
    {
        return $query->where('stock_id', $stockId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function scopeWithMinQuantity($query, $quantity)
    {
        return $query->where('quantity', '>=', $quantity);
    }

    public function scopeWithMinAmount($query, $amount)
    {
        return $query->where('total_amount', '>=', $amount);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->transaction_date) {
                $transaction->transaction_date = now();
            }
            if (!$transaction->reference_number) {
                $transaction->reference_number = 'TRX-' . time() . '-' . rand(1000, 9999);
            }
        });
    }
}
