<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\User;
use App\Models\StockOrderItem;
use Carbon\Carbon;

class StockOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'order_number',
        'reference_number',
        'order_date',
        'expected_delivery_date',
        'delivery_date',
        'status',
        'total_amount',
        'payment_status',
        'payment_method',
        'notes',
        'user_id',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'delivery_date' => 'datetime',
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Get the supplier that the order belongs to
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user who created the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved the order
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the order items
     */
    public function items()
    {
        return $this->hasMany(StockOrderItem::class);
    }

    /**
     * Check if the order is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the order is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the order is delivered
     */
    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if the order is cancelled
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the order is overdue
     */
    public function isOverdue()
    {
        return $this->expected_delivery_date && Carbon::now()->gt($this->expected_delivery_date) && !$this->isDelivered();
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved orders
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for delivered orders
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    /**
     * Scope for cancelled orders
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for orders from a specific supplier
     */
    public function scopeFromSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope for orders created by a specific user
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for orders in a date range
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('order_date', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Scope for overdue orders
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('expected_delivery_date')
            ->where('expected_delivery_date', '<', Carbon::now())
            ->whereNotIn('status', ['delivered', 'cancelled']);
    }

    /**
     * Calculate the total amount of the order
     */
    public function calculateTotal()
    {
        $total = $this->items()->sum(\DB::raw('quantity * unit_price'));
        $this->update(['total_amount' => $total]);
        return $total;
    }

    /**
     * Mark the order as approved
     */
    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => Carbon::now()
        ]);
    }

    /**
     * Mark the order as delivered
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivery_date' => Carbon::now()
        ]);
    }

    /**
     * Cancel the order
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}
