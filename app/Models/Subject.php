<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'department',
        'credits',
        'level',
        'syllabus',
        'passing_grade',
        'active'
    ];

    protected $casts = [
        'credits' => 'integer',
        'level' => 'integer',
        'passing_grade' => 'decimal:2',
        'active' => 'boolean'
    ];

    // Relationships
    public function academicTerms(): BelongsToMany
    {
        return $this->belongsToMany(AcademicTerm::class, 'term_subjects')
                    ->withPivot(['status', 'capacity', 'enrolled_count'])
                    ->withTimestamps();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subject_teachers')
                    ->wherePivot('role', 'teacher')
                    ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(SubjectEnrollment::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'subject_prerequisites', 'subject_id', 'prerequisite_id')
                    ->withTimestamps();
    }

    public function isPrerequisiteFor(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'subject_prerequisites', 'prerequisite_id', 'subject_id')
                    ->withTimestamps();
    }

    // Status Management
    public function isActive(): bool
    {
        return $this->active;
    }

    public function activate(): void
    {
        $this->update(['active' => true]);
    }

    public function deactivate(): void
    {
        if ($this->hasActiveEnrollments()) {
            throw new \InvalidArgumentException('Cannot deactivate subject with active enrollments');
        }
        $this->update(['active' => false]);
    }

    // Prerequisite Management
    public function hasPrerequisite(Subject $subject): bool
    {
        return $this->prerequisites()->where('prerequisite_id', $subject->id)->exists();
    }

    public function addPrerequisite(Subject $subject): void
    {
        if (!$this->hasPrerequisite($subject)) {
            $this->prerequisites()->attach($subject);
        }
    }

    public function removePrerequisite(Subject $subject): void
    {
        $this->prerequisites()->detach($subject);
    }

    // Performance Analytics
    public function getPassRate(?int $termId = null): float
    {
        $query = $this->grades();
        
        if ($termId) {
            $query->where('academic_term_id', $termId);
        }

        $total = $query->count();
        if ($total === 0) {
            return 0;
        }

        $passing = $query->where('score', '>=', $this->passing_grade)->count();
        return ($passing / $total) * 100;
    }

    public function getAverageGrade(?int $termId = null): float
    {
        $query = $this->grades();
        
        if ($termId) {
            $query->where('academic_term_id', $termId);
        }

        return $query->avg('score') ?? 0;
    }

    public function getEnrollmentStatistics(?int $termId = null): array
    {
        $query = $this->enrollments();
        
        if ($termId) {
            $query->where('academic_term_id', $termId);
        }

        return [
            'total' => $query->count(),
            'active' => $query->where('status', 'active')->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'dropped' => $query->where('status', 'dropped')->count()
        ];
    }

    public function hasActiveEnrollments(): bool
    {
        return $this->enrollments()
                    ->where('status', 'active')
                    ->exists();
    }

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByCredits($query, int $credits)
    {
        return $query->where('credits', $credits);
    }

    public function scopeWithPrerequisites($query)
    {
        return $query->has('prerequisites');
    }

    public function scopeWithoutPrerequisites($query)
    {
        return $query->doesntHave('prerequisites');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Model Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subject) {
            if (!isset($subject->active)) {
                $subject->active = true;
            }
            if (!isset($subject->passing_grade)) {
                $subject->passing_grade = 60.00;
            }
        });

        static::deleting(function ($subject) {
            if ($subject->hasActiveEnrollments()) {
                throw new \InvalidArgumentException('Cannot delete subject with active enrollments');
            }
        });
    }
}
