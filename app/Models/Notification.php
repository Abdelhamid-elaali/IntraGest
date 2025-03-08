<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'priority',
        'data',
        'read_at',
        'expires_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isUnread()
    {
        return is_null($this->read_at);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isHighPriority()
    {
        return $this->priority === 'high';
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    // Static methods for creating common notifications
    public static function paymentDue($user, $payment)
    {
        return static::create([
            'user_id' => $user->id,
            'title' => 'Payment Due',
            'message' => "You have a payment of {$payment->amount} due on {$payment->due_date->format('Y-m-d')}",
            'type' => 'payment',
            'priority' => 'high',
            'data' => [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'due_date' => $payment->due_date
            ],
            'expires_at' => $payment->due_date
        ]);
    }

    public static function leaveRequestStatus($user, $leaveRequest)
    {
        return static::create([
            'user_id' => $user->id,
            'title' => 'Leave Request ' . ucfirst($leaveRequest->status),
            'message' => "Your leave request for {$leaveRequest->start_date->format('Y-m-d')} to {$leaveRequest->end_date->format('Y-m-d')} has been {$leaveRequest->status}",
            'type' => 'leave_request',
            'priority' => 'medium',
            'data' => [
                'leave_request_id' => $leaveRequest->id,
                'status' => $leaveRequest->status,
                'reviewer_id' => $leaveRequest->reviewed_by
            ]
        ]);
    }

    public static function mealScheduleChange($user, $meal)
    {
        return static::create([
            'user_id' => $user->id,
            'title' => 'Meal Schedule Update',
            'message' => "The {$meal->meal_type} schedule for {$meal->date->format('Y-m-d')} has been updated",
            'type' => 'meal',
            'priority' => 'medium',
            'data' => [
                'meal_id' => $meal->id,
                'meal_type' => $meal->meal_type,
                'date' => $meal->date,
                'serving_time' => $meal->serving_time
            ]
        ]);
    }

    public static function lowStock($user, $stock)
    {
        return static::create([
            'user_id' => $user->id,
            'title' => 'Low Stock Alert',
            'message' => "Stock item {$stock->name} is running low (Current: {$stock->quantity}, Minimum: {$stock->minimum_quantity})",
            'type' => 'stock',
            'priority' => 'high',
            'data' => [
                'stock_id' => $stock->id,
                'current_quantity' => $stock->quantity,
                'minimum_quantity' => $stock->minimum_quantity
            ]
        ]);
    }

    public static function roomAssignment($user, $allocation)
    {
        return static::create([
            'user_id' => $user->id,
            'title' => 'Room Assignment',
            'message' => "You have been assigned to Room {$allocation->room->name} from {$allocation->start_date->format('Y-m-d')}",
            'type' => 'room',
            'priority' => 'high',
            'data' => [
                'allocation_id' => $allocation->id,
                'room_id' => $allocation->room_id,
                'room_name' => $allocation->room->name,
                'start_date' => $allocation->start_date
            ]
        ]);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                    ->where('expires_at', '<=', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($notification) {
            if (!array_key_exists('priority', $notification->attributes)) {
                $notification->priority = 'medium';
            }
        });
    }
}
