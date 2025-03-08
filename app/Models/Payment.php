<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\PaymentItem;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'payment_type',
        'payment_method',
        'status',
        'due_date',
        'payment_date',
        'transaction_id',
        'notes',
        'processed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function items()
    {
        return $this->hasMany(PaymentItem::class);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isRefunded()
    {
        return $this->status === 'refunded';
    }

    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date->isPast();
    }

    public function getDaysOverdue()
    {
        return $this->isOverdue() ? $this->due_date->diffInDays(now()) : 0;
    }

    public function markAsCompleted($transactionId = null, $processorId = null)
    {
        $this->update([
            'status' => 'completed',
            'payment_date' => now(),
            'transaction_id' => $transactionId,
            'processed_by' => $processorId
        ]);
    }

    public function markAsFailed($notes = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $notes
        ]);
    }

    public function refund($notes = null, $processorId = null)
    {
        $this->update([
            'status' => 'refunded',
            'notes' => $notes,
            'processed_by' => $processorId
        ]);
    }

    public function addItem($description, $amount, $category)
    {
        return $this->items()->create([
            'description' => $description,
            'amount' => $amount,
            'category' => $category
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                    ->where('due_date', '<', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('due_date', [$startDate, $endDate]);
    }
}
