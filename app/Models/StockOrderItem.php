<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\StockOrder;
use App\Models\Stock;

class StockOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_order_id',
        'stock_id',
        'quantity',
        'unit_price',
        'received_quantity',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2'
    ];

    /**
     * Get the order that this item belongs to
     */
    public function order()
    {
        return $this->belongsTo(StockOrder::class, 'stock_order_id');
    }

    /**
     * Get the stock item
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Calculate the total price for this item
     */
    public function getTotalPrice()
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Calculate the total received price for this item
     */
    public function getTotalReceivedPrice()
    {
        return $this->received_quantity * $this->unit_price;
    }

    /**
     * Check if the item is fully received
     */
    public function isFullyReceived()
    {
        return $this->received_quantity >= $this->quantity;
    }

    /**
     * Check if the item is partially received
     */
    public function isPartiallyReceived()
    {
        return $this->received_quantity > 0 && $this->received_quantity < $this->quantity;
    }

    /**
     * Check if the item is not received at all
     */
    public function isNotReceived()
    {
        return $this->received_quantity == 0;
    }

    /**
     * Get the remaining quantity to be received
     */
    public function getRemainingQuantity()
    {
        return $this->quantity - $this->received_quantity;
    }
}
