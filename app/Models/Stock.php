<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Supplier;
use App\Models\StockTransaction;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'quantity',
        'minimum_quantity',
        'unit_price',
        'unit_type',
        'expiry_date',
        'supplier_id'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'expiry_date' => 'date'
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    // Helper methods
    public function needsRestock()
    {
        return $this->quantity <= $this->minimum_quantity;
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon($days = 7)
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->diffInDays(now()) <= $days;
    }

    public function addStock($quantity, $userId, $notes = null)
    {
        $this->increment('quantity', $quantity);
        
        return $this->transactions()->create([
            'type' => 'in',
            'quantity' => $quantity,
            'notes' => $notes,
            'user_id' => $userId
        ]);
    }

    public function removeStock($quantity, $userId, $notes = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('Insufficient stock quantity');
        }

        $this->decrement('quantity', $quantity);
        
        return $this->transactions()->create([
            'type' => 'out',
            'quantity' => $quantity,
            'notes' => $notes,
            'user_id' => $userId
        ]);
    }

    public function getTotalValue()
    {
        return $this->quantity * $this->unit_price;
    }
}
