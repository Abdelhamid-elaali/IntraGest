<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stock;
use App\Models\User;

class StockTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'type',
        'quantity',
        'unit_price',
        'total_amount',
        'user_id',
        'notes',
        'reference_number',
        'transaction_date'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
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
    public function scopeStockIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeStockOut($query)
    {
        return $query->where('type', 'out');
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
