<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'description',
        'amount',
        'category',
        'tax_rate',
        'tax_amount',
        'discount_rate',
        'discount_amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_rate' => 'decimal:2',
        'discount_amount' => 'decimal:2'
    ];

    // Relationships
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Helper methods
    public function calculateTax()
    {
        if ($this->tax_rate) {
            $this->tax_amount = $this->amount * ($this->tax_rate / 100);
            $this->save();
        }
        return $this->tax_amount ?? 0;
    }

    public function calculateDiscount()
    {
        if ($this->discount_rate) {
            $this->discount_amount = $this->amount * ($this->discount_rate / 100);
            $this->save();
        }
        return $this->discount_amount ?? 0;
    }

    public function getNetAmount()
    {
        return $this->amount + ($this->tax_amount ?? 0) - ($this->discount_amount ?? 0);
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeWithTax($query)
    {
        return $query->whereNotNull('tax_rate');
    }

    public function scopeWithDiscount($query)
    {
        return $query->whereNotNull('discount_rate');
    }

    public function scopeAmountGreaterThan($query, $amount)
    {
        return $query->where('amount', '>', $amount);
    }

    public function scopeAmountLessThan($query, $amount)
    {
        return $query->where('amount', '<', $amount);
    }
}
