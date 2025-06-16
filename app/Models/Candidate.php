<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'cin',
        'email',
        'phone',
        'nationality',
        'gender',
        'birth_date',
        'address',
        'city',
        'training_level',
        'specialization',
        'status',
        'notes',
        'application_date',
        'user_id',
        'academic_year',
        'income_level',
        'has_disability',
        'family_status',
        'siblings_count',
        'guardian_first_name',
        'guardian_last_name',
        'guardian_dob',
        'guardian_profession',
        'guardian_phone',
        'physical_condition',
        'educational_level',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'application_date' => 'date',
        'has_disability' => 'boolean',
        'family_status' => 'array',
        'siblings_count' => 'integer',
        'guardian_dob' => 'date',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the documents for the candidate.
     */
    public function documents()
    {
        return $this->hasMany(CandidateDocument::class);
    }
    
    /**
     * Get the criteria associated with the candidate (many-to-many relationship).
     */
    public function criteria()
    {
        return $this->belongsToMany(Criteria::class, 'candidate_criteria')
            ->withPivot(['score', 'note'])
            ->withTimestamps();
    }

    /**
     * Get the category weights for this candidate.
     */
    public function criteriaWeights()
    {
        return $this->hasMany(CandidateCategoryWeight::class);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getAge()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}
