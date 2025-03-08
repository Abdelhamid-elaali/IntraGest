<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'absence_id',
        'request_type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'supporting_documents',
        'reviewed_by',
        'reviewed_at',
        'review_notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'supporting_documents' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absence()
    {
        return $this->belongsTo(Absence::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function getDurationInDays()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function hasOverlap($startDate, $endDate)
    {
        return $this->start_date->lte($endDate) && $this->end_date->gte($startDate);
    }

    public function approve($reviewerId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes
        ]);

        // Create an absence record if approved
        if (!$this->absence_id) {
            $absence = Absence::create([
                'user_id' => $this->user_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'type' => $this->request_type,
                'reason' => $this->reason,
                'status' => 'approved',
                'supporting_documents' => $this->supporting_documents,
                'approved_by' => $reviewerId,
                'approved_at' => now(),
                'notes' => $notes
            ]);

            $this->update(['absence_id' => $absence->id]);
        }
    }

    public function reject($reviewerId, $notes)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes
        ]);
    }

    public function cancel($notes = null)
    {
        $this->update([
            'status' => 'cancelled',
            'review_notes' => $notes ? ($this->review_notes . "\nCancelled: " . $notes) : $this->review_notes
        ]);

        // If there's an associated absence, mark it as cancelled
        if ($this->absence_id) {
            $this->absence->update([
                'status' => 'cancelled',
                'notes' => $notes ? ($this->absence->notes . "\nCancelled: " . $notes) : $this->absence->notes
            ]);
        }
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('request_type', $type);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q) use ($startDate, $endDate) {
                  $q->where('start_date', '<=', $startDate)
                    ->where('end_date', '>=', $endDate);
              });
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($request) {
            if (!$request->status) {
                $request->status = 'pending';
            }
        });
    }
}
