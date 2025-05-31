<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Supplier;
use App\Models\StockTransaction;
use App\Models\Department;
use App\Models\StockCategory;
use App\Models\StockOrderItem;
use Carbon\Carbon;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'description',
        'quantity',
        'maximum_quantity',
        'minimum_quantity',
        'unit_type',
        'unit_price',
        'expiry_date',
        'supplier_id',
        'department_id',
        'category_id',
        'subcategory_id',
        'location',
        'barcode',
        'image',
        'vat_rate',
        'status',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'price_with_vat',
        'stock_level_status',
        'stock_percentage',
        'stock_status',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the department that this stock belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the category that this stock belongs to
     */
    public function category()
    {
        return $this->belongsTo(StockCategory::class, 'category_id');
    }

    /**
     * Get the subcategory that this stock belongs to
     */
    public function subcategory()
    {
        return $this->belongsTo(StockCategory::class, 'subcategory_id');
    }

    /**
     * Get the transactions for this stock
     */
    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
    
    /**
     * Get the order items for this stock
     */
    public function orderItems()
    {
        return $this->hasMany(StockOrderItem::class);
    }

    // Helper methods
    /**
     * Calculate the price including VAT (using default VAT rate)
     */
    protected function priceWithVat(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->unit_price * 1.20 // Using 20% VAT as default
        );
    }

    /**
     * Calculate the stock level percentage
     */
    protected function stockPercentage(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->maximum_quantity <= 0) {
                    return 0;
                }

                $percentage = ($this->quantity / $this->maximum_quantity) * 100;
                return round(min($percentage, 100), 1);
            }
        );
    }

    /**
     * Get the stock status (critical, low, normal)
     */
    protected function stockStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                $percentage = $this->stock_percentage;

                if ($percentage <= 10) {
                    return 'critical';
                } elseif ($percentage <= 15) {
                    return 'low';
                } else {
                    return 'normal';
                }
            }
        );
    }

    /**
     * Check if stock needs to be reordered
     */
    public function needsRestock()
    {
        return $this->quantity <= $this->minimum_quantity;
    }
    
    /**
     * Get the stock level status (red, yellow, green)
     */
    public function getStockLevelStatusAttribute()
    {
        $percentage = $this->stock_percentage;
        
        if ($percentage <= 10) {
            return 'red';
        } elseif ($percentage <= 15) {
            return 'yellow';
        } else {
            return 'green';
        }
    }
    
    /**
     * Get the price including VAT (legacy method for backward compatibility)
     */
    public function getPriceWithVatAttribute()
    {
        return round($this->unit_price * 1.20, 2); // Using 20% VAT as default
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
    
    /**
     * Get total value including VAT
     */
    public function getTotalValueWithVat()
    {
        return $this->quantity * $this->price_with_vat;
    }
    
    /**
     * Transfer stock to another department
     */
    public function transferToDepartment($departmentId, $quantity, $userId, $notes = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('Insufficient stock quantity for transfer');
        }
        
        // Decrease stock from current item
        $this->decrement('quantity', $quantity);
        
        // Record the transaction
        $this->transactions()->create([
            'type' => 'transfer_out',
            'quantity' => $quantity,
            'unit_price' => $this->unit_price,
            'user_id' => $userId,
            'notes' => $notes ?? 'Stock transfer to department ID: ' . $departmentId,
            'reference_number' => 'TRF-' . time() . '-' . rand(1000, 9999),
        ]);
        
        return true;
    }
    
    /**
     * Scope for low stock items (below 15%)
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('(quantity * 100.0) / NULLIF(maximum_quantity, 0) <= 15');
    }
    
    /**
     * Scope for critical stock items (below 10%)
     */
    public function scopeCriticalStock($query)
    {
        return $query->whereRaw('(quantity * 100.0) / NULLIF(maximum_quantity, 0) <= 10');
    }
    
    /**
     * Scope for items by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
