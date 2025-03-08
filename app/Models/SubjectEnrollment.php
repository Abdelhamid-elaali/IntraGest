<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Subject;
use App\Models\AcademicTerm;
use App\Models\Grade;

class SubjectEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'academic_term_id',
        'approver_id',
        'status',
        'enrollment_date',
        'approved_at',
        'dropped_at',
        'notes',
        'drop_reason'
    ];

    protected $casts = [
        'enrollment_date' => 'datetime',
        'approved_at' => 'datetime',
        'dropped_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'student_id')
                    ->where('subject_id', $this->subject_id)
                    ->where('academic_term_id', $this->academic_term_id);
    }

    // Status Management
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDropped(): bool
    {
        return $this->status === 'dropped';
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function approve(int $approverId): void
    {
        $this->update([
            'status' => 'active',
            'approver_id' => $approverId,
            'approved_at' => now()
        ]);
    }

    public function drop(?string $reason = null): void
    {
        $this->update([
            'status' => 'dropped',
            'dropped_at' => now(),
            'drop_reason' => $reason
        ]);
    }

    // Grade Management
    public function getCurrentGrade(): ?Grade
    {
        return $this->grades()
            ->latest()
            ->first();
    }

    public function getAverageGrade(): float
    {
        return $this->grades()
            ->avg('score') ?? 0;
    }

    public function hasPassingGrade(): bool
    {
        $currentGrade = $this->getCurrentGrade();
        return $currentGrade ? $currentGrade->isPassingGrade() : false;
    }

    // Enrollment Rules
    public function canDrop(): bool
    {
        return $this->isActive() && 
               $this->academicTerm->isDropPeriodOpen();
    }

    public function hasPrerequisites(): bool
    {
        $prerequisites = $this->subject->prerequisites;
        if ($prerequisites->isEmpty()) {
            return true;
        }

        return static::where('student_id', $this->student_id)
            ->whereIn('subject_id', $prerequisites->pluck('id'))
            ->where('status', 'active')
            ->exists();
    }

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDropped($query)
    {
        return $query->where('status', 'dropped');
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeForTerm($query, $termId)
    {
        return $query->where('academic_term_id', $termId);
    }

    public function scopeApprovedBy($query, $approverId)
    {
        return $query->where('approver_id', $approverId);
    }

    public function scopeEnrolledBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('enrollment_date', [$startDate, $endDate]);
    }

    public function scopeDroppedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('dropped_at', [$startDate, $endDate]);
    }

    // Model Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (!$enrollment->enrollment_date) {
                $enrollment->enrollment_date = now();
            }
        });

        static::saving(function ($enrollment) {
            // Prevent enrolling in inactive subjects
            if (!$enrollment->subject->isActive()) {
                throw new \InvalidArgumentException('Cannot enroll in an inactive subject');
            }

            // Prevent enrolling outside registration period
            if ($enrollment->isDirty('status') && $enrollment->status === 'active') {
                if (!$enrollment->academicTerm->isRegistrationOpen()) {
                    throw new \InvalidArgumentException('Registration period is closed');
                }
            }

            // Prevent dropping outside drop period
            if ($enrollment->isDirty('status') && $enrollment->status === 'dropped') {
                if (!$enrollment->academicTerm->isDropPeriodOpen()) {
                    throw new \InvalidArgumentException('Drop period is closed');
                }
            }

            // Check prerequisites
            if ($enrollment->isDirty('status') && $enrollment->status === 'active') {
                if (!$enrollment->hasPrerequisites()) {
                    throw new \InvalidArgumentException('Prerequisites not met');
                }
            }
        });
    }
}
