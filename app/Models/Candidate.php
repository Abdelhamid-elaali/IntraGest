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
        'birth_date',
        'email',
        'phone',
        'address',
        'city',
        'status',
        'notes',
        'application_date',
        'user_id',
        'academic_year',
        'specialization',
        'nationality',
        'distance',
        'income_level',
        'training_level',
        'has_disability',
        'family_status',
        'score',
        'siblings_count',
        'guardian_first_name',
        'guardian_last_name',
        'guardian_dob',
        'guardian_profession',
        'guardian_phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'application_date' => 'date',
        'has_disability' => 'boolean',
        'score' => 'float',
        'family_status' => 'array',
        'siblings_count' => 'integer',
        'guardian_dob' => 'date',
    ];

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
        return $this->birth_date->age;
    }
    
    // Accessors for fields that don't exist in the database but are displayed in the view
    public function getNameAttribute()
    {
        // Directly access the attributes array to bypass any potential overrides
        $firstName = $this->getAttributeFromArray('first_name');
        $lastName = $this->getAttributeFromArray('last_name');
        
        if (!empty($firstName) && !empty($lastName)) {
            return $firstName . ' ' . $lastName;
        } elseif (!empty($firstName)) {
            return $firstName;
        } elseif (!empty($lastName)) {
            return $lastName;
        } else {
            return 'Unnamed Candidate';
        }
    }
}
