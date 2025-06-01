<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Student;
use App\Models\AbsenceType;
use Carbon\Carbon;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'start_date',
        'end_date',
        'type',
        'absence_type_id',
        'reason',
        'status',
        'supporting_documents',
        'duration',
        'approver_id',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'supporting_documents' => 'array'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function absenceType()
    {
        return $this->belongsTo(AbsenceType::class);
    }
    
    public function user()
    {
        return $this->hasOneThrough(User::class, Student::class, 'id', 'id', 'student_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
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

    public function getDurationInDays()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function isOngoing()
    {
        return now()->between($this->start_date, $this->end_date);
    }

    public function hasOverlap($startDate, $endDate)
    {
        return $this->start_date->lte($endDate) && $this->end_date->gte($startDate);
    }

    public function approve($approverId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'approver_id' => $approverId,
            'approved_at' => now(),
            'notes' => $notes
        ]);
        
        // Notify the student if needed
        if ($this->student && $this->student->user) {
            $this->student->user->notify(new \App\Notifications\AbsenceApproved($this));
        }
        
        return $this;
    }

    public function reject($approverId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'approver_id' => $approverId,
            'approved_at' => now(),
            'notes' => $reason
        ]);
        
        // Notify the student if needed
        if ($this->student && $this->student->user) {
            $this->student->user->notify(new \App\Notifications\AbsenceRejected($this));
        }
        
        return $this;
    }

    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
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
    
    /**
     * Get absences for a specific student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
    
    /**
     * Get absences that require attention (repeated absences)
     */
    public function scopeRequiringAttention($query, $threshold = 3, $days = 30)
    {
        $date = Carbon::now()->subDays($days);
        
        return $query->select('student_id')
            ->where('start_date', '>=', $date)
            ->groupBy('student_id')
            ->havingRaw('COUNT(*) >= ?', [$threshold]);
    }
    
    /**
     * Get the formatted duration for display
     */
    public function getFormattedDuration()
    {
        if ($this->type === 'late' && $this->duration) {
            return $this->duration . ' minutes';
        }
        
        return $this->getDurationInDays() . ' day(s)';
    }
    
    /**
     * Get the status badge class
     */
    public function getStatusBadgeClass()
    {
        return [            
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800'
        ][$this->status] ?? 'bg-gray-100 text-gray-800';
    }
    
    /**
     * Get the type badge class
     */
    public function getTypeBadgeClass()
    {
        return [
            'excused' => 'bg-blue-100 text-blue-800',
            'unexcused' => 'bg-red-100 text-red-800',
            'late' => 'bg-yellow-100 text-yellow-800',
            'medical' => 'bg-green-100 text-green-800',
            'family' => 'bg-purple-100 text-purple-800'
        ][$this->type] ?? 'bg-gray-100 text-gray-800';
    }
}
