<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Stock;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'tax_number',
        'notes',
        'status'
    ];

    // Relationships
    public function stocks()
    {
        return $this->hasMany(Stock::class);
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
        return $this->stocks()
            ->withSum('transactions', 'quantity')
            ->where('stock_transactions.type', 'in')
            ->get()
            ->sum('transactions_sum_quantity');
    }

    public function getActiveStocks()
    {
        return $this->stocks()->where('quantity', '>', 0)->get();
    }

    public function getLowStockItems()
    {
        return $this->stocks()->whereRaw('quantity <= minimum_quantity')->get();
    }

    public function getExpiringStocks($days = 30)
    {
        return $this->stocks()
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($days))
            ->where('expiry_date', '>', now())
            ->get();
    }
}
