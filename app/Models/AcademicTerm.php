<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\SubjectEnrollment;
use Carbon\Carbon;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'academic_year',
        'start_date',
        'end_date',
        'registration_deadline',
        'drop_deadline',
        'grading_deadline',
        'is_current',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
        'drop_deadline' => 'date',
        'grading_deadline' => 'date',
        'is_current' => 'boolean'
    ];

    // Relationships
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'term_subjects')
                    ->withPivot(['status'])
                    ->withTimestamps();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function enrollments()
    {
        return $this->hasMany(SubjectEnrollment::class);
    }

    // Status Management
    public function isCurrent(): bool
    {
        return $this->is_current;
    }

    public function markAsCurrent(): void
    {
        static::query()->where('is_current', true)->update(['is_current' => false]);
        $this->update(['is_current' => true, 'status' => 'active']);
    }

    public function updateStatus(): void
    {
        $now = Carbon::now();
        
        if ($now->lt($this->start_date)) {
            $this->status = 'upcoming';
        } elseif ($now->gt($this->end_date)) {
            $this->status = 'completed';
        } else {
            $this->status = 'active';
        }
        
        $this->save();
    }

    // Period Management
    public function isRegistrationOpen(): bool
    {
        return Carbon::now()->lt($this->registration_deadline);
    }

    public function isDropPeriodOpen(): bool
    {
        return Carbon::now()->lt($this->drop_deadline);
    }

    public function isGradingOpen(): bool
    {
        return Carbon::now()->lt($this->grading_deadline);
    }

    // Academic Analytics
    public function getEnrollmentStatistics(): array
    {
        $enrollments = $this->enrollments();
        
        return [
            'total' => $enrollments->count(),
            'active' => $enrollments->where('status', 'active')->count(),
            'pending' => $enrollments->where('status', 'pending')->count(),
            'dropped' => $enrollments->where('status', 'dropped')->count(),
        ];
    }

    public function getGradeStatistics(): array
    {
        $grades = $this->grades();
        $total = $grades->count();
        
        if ($total === 0) {
            return [
                'average' => 0,
                'pass_rate' => 0,
                'fail_rate' => 0,
                'highest' => 0,
                'lowest' => 0
            ];
        }

        return [
            'average' => $grades->avg('score'),
            'pass_rate' => ($grades->where('score', '>=', 60)->count() / $total) * 100,
            'fail_rate' => ($grades->where('score', '<', 60)->count() / $total) * 100,
            'highest' => $grades->max('score'),
            'lowest' => $grades->min('score')
        ];
    }

    public function getSubjectStatistics(): array
    {
        return [
            'total' => $this->subjects()->count(),
            'active' => $this->subjects()->wherePivot('status', 'active')->count(),
            'full' => $this->subjects()->whereHas('enrollments', function($query) {
                $query->groupBy('subject_id')
                      ->havingRaw('count(*) >= subjects.capacity');
            })->count()
        ];
    }

    // Validation Rules
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($term) {
            if (!isset($term->status)) {
                $term->status = Carbon::now()->lt($term->start_date) ? 'upcoming' : 'active';
            }
        });

        static::saving(function ($term) {
            // Date validation
            if ($term->end_date->lt($term->start_date)) {
                throw new \InvalidArgumentException('End date must be after start date');
            }

            if ($term->registration_deadline->gt($term->end_date)) {
                throw new \InvalidArgumentException('Registration deadline must be before term end');
            }

            if ($term->drop_deadline->gt($term->end_date)) {
                throw new \InvalidArgumentException('Drop deadline must be before term end');
            }

            if ($term->grading_deadline->lt($term->end_date)) {
                throw new \InvalidArgumentException('Grading deadline must be after term end');
            }

            // Status validation
            if ($term->is_current && $term->status === 'completed') {
                throw new \InvalidArgumentException('Current term cannot be completed');
            }
        });
    }

    // Query Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('registration_deadline', '>', Carbon::now());
    }

    public function scopeDropPeriodOpen($query)
    {
        return $query->where('drop_deadline', '>', Carbon::now());
    }

    public function scopeGradingOpen($query)
    {
        return $query->where('grading_deadline', '>', Carbon::now());
    }
}
