<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\AcademicTerm;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_name',
        'academic_term_id',
        'grader_id',
        'score',
        'letter_grade',
        'grade_point',
        'assessment_type',
        'weight',
        'comments',
        'is_final',
        'finalized_at'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'grade_point' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_final' => 'boolean',
        'finalized_at' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'grader_id');
    }

    // Helper methods
    public function calculateLetterGrade(): string
    {
        $score = $this->score;
        
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }

    public function calculateGradePoint(): float
    {
        return match($this->letter_grade) {
            'A' => 4.0,
            'B' => 3.0,
            'C' => 2.0,
            'D' => 1.0,
            default => 0.0,
        };
    }

    public function updateLetterGrade(): void
    {
        $this->letter_grade = $this->calculateLetterGrade();
        $this->grade_point = $this->calculateGradePoint();
        $this->save();
    }

    public function isPassingGrade(): bool
    {
        return $this->score >= 60;
    }

    public function getWeightedScore(): float
    {
        return $this->score * ($this->weight / 100);
    }

    public function finalize(): void
    {
        if (!$this->is_final) {
            $this->update([
                'is_final' => true,
                'finalized_at' => now()
            ]);
        }
    }

    public function revertFinalization(): void
    {
        if ($this->is_final) {
            $this->update([
                'is_final' => false,
                'finalized_at' => null
            ]);
        }
    }

    // Scopes
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForTerm($query, $termId)
    {
        return $query->where('academic_term_id', $termId);
    }

    public function scopeByAssessmentType($query, $type)
    {
        return $query->where('assessment_type', $type);
    }

    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_final', false);
    }

    public function scopePassingGrades($query)
    {
        return $query->where('score', '>=', 60);
    }

    public function scopeFailingGrades($query)
    {
        return $query->where('score', '<', 60);
    }

    public function scopeGradedBy($query, $userId)
    {
        return $query->where('grader_id', $userId);
    }

    public function scopeGradedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('finalized_at', [$startDate, $endDate]);
    }

    public function scopeWithLetterGrade($query, $letter)
    {
        return $query->where('letter_grade', $letter);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($grade) {
            if (!$grade->grader_id) {
                $grade->grader_id = auth()->id();
            }
            if (!$grade->letter_grade) {
                $grade->letter_grade = $grade->calculateLetterGrade();
                $grade->grade_point = $grade->calculateGradePoint();
            }
            if (!isset($grade->weight)) {
                $grade->weight = 100.00;
            }
        });

        static::updating(function ($grade) {
            if ($grade->isDirty('score')) {
                $grade->letter_grade = $grade->calculateLetterGrade();
                $grade->grade_point = $grade->calculateGradePoint();
            }
        });
    }
}
